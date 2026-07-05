<?php

namespace App\Services;

use App\Models\Resume;
use App\Models\JobDescription;
use App\Models\AtsScore;

class AtsScoreService
{
    /**
     * ATS stop words to exclude from keyword matching
     */
    private const STOP_WORDS = [
        'a', 'an', 'and', 'are', 'as', 'at', 'be', 'been', 'being', 'by',
        'for', 'from', 'had', 'has', 'have', 'he', 'her', 'him', 'his',
        'how', 'i', 'if', 'in', 'is', 'it', 'its', 'me', 'my', 'no',
        'not', 'of', 'on', 'or', 'our', 'out', 'she', 'so', 'than', 'that',
        'the', 'their', 'them', 'then', 'there', 'they', 'this', 'to',
        'up', 'us', 'was', 'we', 'were', 'what', 'when', 'where', 'which',
        'who', 'will', 'with', 'would', 'you', 'your', 'also', 'but', 'can',
        'do', 'did', 'does', 'each', 'few', 'get', 'got', 'had', 'has',
        'have', 'he', 'into', 'just', 'like', 'more', 'most', 'may', 'must',
        'need', 'new', 'now', 'one', 'only', 'other', 'own', 'same', 'such',
        'than', 'too', 'under', 'very', 'via', 'while', 'use', 'used',
        'using', 'well', 'work', 'years', 'year', 'etc',
    ];

    /**
     * High-value ATS sections with their weights
     */
    private const SECTION_WEIGHTS = [
        'experience'     => 35,
        'skills'         => 30,
        'education'      => 15,
        'projects'       => 10,
        'certifications' => 10,
    ];

    /**
     * Score a resume against a job description and persist the result.
     */
    public function score(Resume $resume, JobDescription $jd): AtsScore
    {
        $resumeText  = $this->getResumeText($resume);
        $jdKeywords  = $this->extractKeywords($jd->raw_text);
        $resumeWords = $this->extractKeywords($resumeText);

        // --- Keyword matching ---
        $matched = array_values(array_intersect($jdKeywords, $resumeWords));
        $missing = array_values(array_diff($jdKeywords, $resumeWords));

        // --- Base keyword score (0-70) ---
        $keywordScore = count($jdKeywords) > 0
            ? min(70, (int) round((count($matched) / count($jdKeywords)) * 70))
            : 0;

        // --- Section score (0-20) ---
        $sectionScore  = $this->scoreSections($resume, $jdKeywords);
        $sectionScores = $sectionScore['breakdown'];

        // --- Flag issues (0-10) ---
        $flags      = $this->detectIssues($resume, $resumeText, $jdKeywords);
        $flagPenalty = min(10, count($flags) * 3);
        $flagScore  = 10 - $flagPenalty;

        $overall = min(100, $keywordScore + $sectionScore['total'] + $flagScore);

        // Persist
        $atsScore = AtsScore::updateOrCreate(
            ['job_description_id' => $jd->id],
            [
                'resume_id'        => $resume->id,
                'overall_score'    => $overall,
                'matched_keywords' => array_slice($matched, 0, 50),
                'missing_keywords' => array_slice($missing, 0, 50),
                'flagged_issues'   => $flags,
                'section_scores'   => $sectionScores,
                'scored_at'        => now(),
            ]
        );

        return $atsScore;
    }

    /**
     * Extract and normalize keywords from text.
     * Returns unique, lowercased, stop-word-filtered, min-3-char words + bigrams.
     */
    public function extractKeywords(string $text): array
    {
        // Normalize: lowercase, remove special chars except hyphens
        $text = strtolower($text);
        $text = preg_replace('/[^\w\s\-\+\#]/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', trim($text));

        $words = explode(' ', $text);

        // Filter words
        $words = array_filter($words, function ($word) {
            return strlen($word) >= 3
                && !in_array($word, self::STOP_WORDS)
                && !is_numeric($word);
        });

        $words = array_values(array_unique($words));

        // Extract bigrams (two-word phrases) for compound skills
        $bigrams = $this->extractBigrams($text);

        return array_unique(array_merge($words, $bigrams));
    }

    /**
     * Extract bigrams (two-word tech phrases) from text.
     */
    private function extractBigrams(string $text): array
    {
        $words = explode(' ', $text);
        $bigrams = [];

        for ($i = 0; $i < count($words) - 1; $i++) {
            $w1 = $words[$i];
            $w2 = $words[$i + 1];
            if (strlen($w1) >= 3 && strlen($w2) >= 3
                && !in_array($w1, self::STOP_WORDS)
                && !in_array($w2, self::STOP_WORDS)) {
                $bigrams[] = $w1 . ' ' . $w2;
            }
        }

        return array_unique($bigrams);
    }

    /**
     * Get all resume text concatenated.
     */
    private function getResumeText(Resume $resume): string
    {
        $resume->load([
            'personalInfo', 'experiences', 'skills',
            'education', 'projects', 'certifications', 'languages',
        ]);

        return $resume->full_text;
    }

    /**
     * Score individual resume sections against JD keywords.
     */
    private function scoreSections(Resume $resume, array $jdKeywords): array
    {
        $breakdown = [];
        $totalContribution = 0;

        // Experience section
        $expText = '';
        foreach ($resume->experiences as $exp) {
            $expText .= ' ' . $exp->job_title . ' ' . $exp->company;
            if ($exp->bullets) {
                $expText .= ' ' . implode(' ', $exp->bullets);
            }
        }
        $expScore = $this->sectionKeywordMatch($expText, $jdKeywords, 35);
        $breakdown['experience'] = $expScore;
        $totalContribution += $expScore;

        // Skills section
        $skillText = implode(' ', $resume->skills->pluck('name')->toArray());
        $skillScore = $this->sectionKeywordMatch($skillText, $jdKeywords, 30);
        $breakdown['skills'] = $skillScore;
        $totalContribution += $skillScore;

        // Education section
        $eduText = '';
        foreach ($resume->education as $edu) {
            $eduText .= ' ' . $edu->degree . ' ' . $edu->field . ' ' . $edu->school;
        }
        $eduScore = $this->sectionKeywordMatch($eduText, $jdKeywords, 15);
        $breakdown['education'] = $eduScore;
        $totalContribution += $eduScore;

        // Projects
        $projText = '';
        foreach ($resume->projects as $proj) {
            $projText .= ' ' . $proj->name . ' ' . $proj->tech_stack . ' ' . $proj->description;
        }
        $projScore = $this->sectionKeywordMatch($projText, $jdKeywords, 10);
        $breakdown['projects'] = $projScore;
        $totalContribution += $projScore;

        // Certifications
        $certText = implode(' ', $resume->certifications->pluck('name')->toArray());
        $certScore = $this->sectionKeywordMatch($certText, $jdKeywords, 10);
        $breakdown['certifications'] = $certScore;
        $totalContribution += $certScore;

        return [
            'total'     => min(20, (int) round($totalContribution / 10)), // scale to 20 pts
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Calculate keyword match percentage for a section text.
     */
    private function sectionKeywordMatch(string $text, array $jdKeywords, int $weight): int
    {
        if (empty(trim($text)) || empty($jdKeywords)) {
            return 0;
        }

        $sectionKeywords = $this->extractKeywords($text);
        $matched = count(array_intersect($jdKeywords, $sectionKeywords));
        $pct = $matched / count($jdKeywords);

        return (int) round($pct * $weight);
    }

    /**
     * Detect ATS-unfriendly issues in the resume.
     */
    private function detectIssues(Resume $resume, string $resumeText, array $jdKeywords): array
    {
        $issues = [];

        // --- Missing section checks ---
        if (!$resume->personalInfo || empty($resume->personalInfo->summary)) {
            $issues[] = [
                'type'    => 'missing_summary',
                'message' => 'No professional summary found. ATS systems look for a keyword-rich summary.',
                'severity' => 'high',
            ];
        }

        if ($resume->experiences->isEmpty()) {
            $issues[] = [
                'type'    => 'missing_experience',
                'message' => 'No work experience entries found.',
                'severity' => 'high',
            ];
        }

        if ($resume->skills->isEmpty()) {
            $issues[] = [
                'type'    => 'missing_skills',
                'message' => 'Skills section is empty. Add relevant skills from the job description.',
                'severity' => 'high',
            ];
        }

        if ($resume->education->isEmpty()) {
            $issues[] = [
                'type'    => 'missing_education',
                'message' => 'Education section is empty.',
                'severity' => 'medium',
            ];
        }

        // --- Summary keyword sparsity ---
        if ($resume->personalInfo && !empty($resume->personalInfo->summary)) {
            $summaryWords = $this->extractKeywords($resume->personalInfo->summary);
            $summaryMatches = count(array_intersect($jdKeywords, $summaryWords));
            if ($summaryMatches < 3 && count($jdKeywords) >= 5) {
                $issues[] = [
                    'type'    => 'sparse_summary',
                    'message' => 'Your summary contains very few keywords from the job description (' . $summaryMatches . ' matched). Consider rewriting it.',
                    'severity' => 'medium',
                ];
            }
        }

        // --- Short bullets ---
        foreach ($resume->experiences as $exp) {
            if (empty($exp->bullets) || count($exp->bullets) < 2) {
                $issues[] = [
                    'type'    => 'short_bullets',
                    'message' => 'Work experience "' . ($exp->job_title ?: 'entry') . '" has fewer than 2 bullet points. Add quantified achievements.',
                    'severity' => 'medium',
                ];
            }
        }

        // --- Missing contact info ---
        if ($resume->personalInfo) {
            if (empty($resume->personalInfo->email)) {
                $issues[] = [
                    'type'    => 'missing_email',
                    'message' => 'No email address found in personal info.',
                    'severity' => 'high',
                ];
            }
            if (empty($resume->personalInfo->phone)) {
                $issues[] = [
                    'type'    => 'missing_phone',
                    'message' => 'No phone number found in personal info.',
                    'severity' => 'medium',
                ];
            }
        }

        // --- Resume length (word count) ---
        $wordCount = str_word_count($resumeText);
        if ($wordCount < 200) {
            $issues[] = [
                'type'    => 'too_short',
                'message' => 'Resume content is very sparse (' . $wordCount . ' words). ATS systems prefer 400–800 words.',
                'severity' => 'high',
            ];
        }

        return $issues;
    }

    /**
     * Compare ATS scores across all versions of resumes for the same user.
     */
    public function compareVersions(array $resumes): array
    {
        $comparison = [];

        foreach ($resumes as $resume) {
            $latest = $resume->latestAtsScore;
            $comparison[] = [
                'resume_id'    => $resume->id,
                'resume_title' => $resume->title,
                'is_primary'   => $resume->is_primary,
                'score'        => $latest?->overall_score ?? null,
                'label'        => $latest?->score_label ?? 'Not scored',
                'color'        => $latest?->score_color ?? '#6b7280',
                'scored_at'    => $latest?->scored_at?->diffForHumans() ?? 'Never',
            ];
        }

        usort($comparison, fn($a, $b) => ($b['score'] ?? -1) <=> ($a['score'] ?? -1));

        return $comparison;
    }
}
