<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $resume->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 24pt;
            margin: 0 0 5px 0;
            color: #111;
            text-transform: uppercase;
        }
        h2 {
            font-size: 14pt;
            margin: 0 0 10px 0;
            color: #555;
            font-weight: normal;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .contact-info {
            font-size: 10pt;
            color: #666;
        }
        .section-title {
            font-size: 13pt;
            font-weight: bold;
            color: #111;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 3px;
        }
        .item {
            margin-bottom: 15px;
        }
        .item-header {
            width: 100%;
        }
        .item-title {
            font-weight: bold;
            float: left;
        }
        .item-date {
            float: right;
            font-size: 10pt;
            color: #666;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .item-subtitle {
            font-style: italic;
            margin-top: 2px;
            margin-bottom: 5px;
        }
        ul {
            margin: 0;
            padding-left: 20px;
        }
        li {
            margin-bottom: 3px;
        }
        .summary {
            margin-bottom: 20px;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ optional($resume->personalInfo)->full_name ?? 'YOUR NAME' }}</h1>
        <h2>{{ optional($resume->personalInfo)->job_title ?? 'Professional Title' }}</h2>
        <div class="contact-info">
            {{ optional($resume->personalInfo)->email }}
            @if(optional($resume->personalInfo)->phone) | {{ $resume->personalInfo->phone }} @endif
            @if(optional($resume->personalInfo)->location) | {{ $resume->personalInfo->location }} @endif
            @if(optional($resume->personalInfo)->linkedin) | {{ $resume->personalInfo->linkedin }} @endif
        </div>
    </div>

    @if(optional($resume->personalInfo)->summary)
    <div class="section-title">Professional Summary</div>
    <div class="summary">
        {{ $resume->personalInfo->summary }}
    </div>
    @endif

    @if($resume->experiences->count() > 0)
    <div class="section-title">Professional Experience</div>
    @foreach($resume->experiences as $exp)
    <div class="item">
        <div class="item-header clearfix">
            <div class="item-title">{{ $exp->job_title }}</div>
            <div class="item-date">{{ $exp->start_date }} - {{ $exp->end_date ?? 'Present' }}</div>
        </div>
        <div class="item-subtitle">{{ $exp->company }} @if($exp->location) - {{ $exp->location }} @endif</div>
        @if(!empty($exp->bullets))
        <ul>
            @foreach($exp->bullets as $bullet)
                <li>{{ $bullet }}</li>
            @endforeach
        </ul>
        @endif
    </div>
    @endforeach
    @endif

    @if($resume->education->count() > 0)
    <div class="section-title">Education</div>
    @foreach($resume->education as $edu)
    <div class="item">
        <div class="item-header clearfix">
            <div class="item-title">{{ $edu->degree }} in {{ $edu->field }}</div>
            <div class="item-date">{{ $edu->start_date }} - {{ $edu->end_date }}</div>
        </div>
        <div class="item-subtitle">{{ $edu->school }} @if($edu->location) - {{ $edu->location }} @endif</div>
    </div>
    @endforeach
    @endif

    @if($resume->skills->count() > 0)
    <div class="section-title">Skills</div>
    <div class="item">
        {{ implode(' • ', $resume->skills->pluck('name')->toArray()) }}
    </div>
    @endif

    @if($resume->projects->count() > 0)
    <div class="section-title">Projects</div>
    @foreach($resume->projects as $proj)
    <div class="item">
        <div class="item-header clearfix">
            <div class="item-title">{{ $proj->name }} @if($proj->tech_stack) | <span style="font-weight:normal;font-style:italic">{{ $proj->tech_stack }}</span> @endif</div>
        </div>
        <div style="margin-top: 5px;">{{ $proj->description }}</div>
    </div>
    @endforeach
    @endif

</body>
</html>
