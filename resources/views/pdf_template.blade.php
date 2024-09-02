<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'thsarabunnew', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        h1, h2, h3 {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #444;
        }
        p {
            margin-bottom: 10px;
        }
        .section-title {
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .info-block {
            margin-bottom: 15px;
        }
        .info-block p {
            margin-bottom: 5px;
        }
    </style>
</head>
{{-- <body>
    <h2 class="h2">{{ $title }}</h2>
    <h3 style="text-align: right;">{{ $date }}</h3>
    <p style="text-align: left;">เรียน นายกองค์การบริหารส่วนจังหวัดสุโขทัย</p>

    <pre>            ข้าพเจ้า {{ $booking_car->name }}                                                                ตำแหน่ง {{ $booking_car->position_r->name }}</pre>

    <pre style="text-align: left;">แผนก {{ $booking_car->departments_r->name }}                                                                        ขออนุญาติใช้รถไปราชการที่ {{ $booking_car->destination }}</pre>
    <pre>เพื่อ {{ $booking_car->purpose }}                                                                         จำนวนคนนั่ง {{ $booking_car->capacity }} คน</pre>
    <pre>โดยออกเดินทางในวันที่ {{ $booking_car->date_start }}                                           เวลา {{ $booking_car->time_start }}</pre>
    <pre>และกลับในวันที่ {{ $booking_car->date_stop }}                                                    เวลา {{ $booking_car->time_stop }}</pre>
    <h3 style="text-align: right;">(ลงชื่อ).................................................ผู้ขออนุญาต</h3>
    <pre>                                                                                                            ({{ $booking_car->name }})</pre><br>
        <h3 style="text-align: right;">(ลงชื่อ).....................................................หัวหน้ากลุ่มงาน</h3>
        <pre>                                                                                                      (.............................................)</pre>
    <pre>                                                                                                               ......./......./.......</pre>
        <h3 style="text-align: left;">เห็นควรอนุญาตให้ใช้รถ ........................ หมายเลขทะเบียน
            {{ $booking_car->car_r->tax_car }}</h3>
        <h3 style="text-align: left;">โดย {{ $booking_car->user_drive_r->name }} เป็นคนขับ</h3>
        <h3 style="text-align: right;">(ลงชื่อ)....................................ผู้จัดรถ</h3>
        <pre>                                                                                                                           ({{ $carofficer->name }})</pre>
            <h3 style="text-align: center;">คำสั่ง อนุญาต</h3>
            <h3 style="text-align: right;">(ลงชื่อ)..........................ผู้อนุญาต</h3>
            <pre>                                                                                                                     (......................................)</pre>
            <pre>                                                                                                                           ....../....../......</pre>
</body> --}}
<body>
    <div class="container">
        <h1 class="text-center">Application Report</h1>

        <div class="info-block">
            <h2>ข้อมูลนักศึกษา</h2>
            <p><strong>ชื่อ:</strong> {{ $application->student->PrefixName ?? 'N/A' }} {{ $application->student->FirstName ?? 'N/A' }} {{ $application->student->LastName ?? 'N/A' }}</p>
            <p><strong>รหัสนักศึกษา:</strong> {{ $application->student->StudentID ?? 'N/A' }}</p>
            <p><strong>หลักสูตร:</strong> {{ $application->student->Course ?? 'N/A' }}</p>
            <p><strong>อีเมล:</strong> {{ $application->student->Email ?? 'N/A' }}</p>
            <p><strong>เบอร์โทร:</strong> {{ $application->student->Phone ?? 'N/A' }}</p>                  <p><strong>เบอร์โทร:</strong> {{ $application->student->Phone ?? 'N/A' }}</p>    
        </div>

        <div class="info-block">
            <h2>ข้อมูลทุนการศึกษาsf</h2>
            <p><strong>ชื่อทุน:</strong> {{ $application->scholarship->ScholarshipName ?? 'N/A' }}</p>
            <p><strong>สถานะ:</strong> {{ $application->Status ?? 'N/A' }}</p>
            <p><strong>วันที่สมัคร:</strong> {{ $application->ApplicationDate ?? 'N/A' }}</p>
        </div>

        <div class="info-block">
            <h2>ที่อยู่</h2>
            @if (!empty($application->addresses))
                @foreach ($application->addresses as $address)
                    <p><strong>ประเภท:</strong> {{ $address->Type ?? 'N/A' }}</p>
                    <p>{{ $address->AddressLine ?? 'N/A' }}, {{ $address->Subdistrict ?? 'N/A' }}, {{ $address->District ?? 'N/A' }}, {{ $address->province ?? 'N/A' }}, {{ $address->PostalCode ?? 'N/A' }}</p>
                @endforeach
            @else
                <p>ไม่มีข้อมูลที่อยู่</p>
            @endif
        </div>

        <div class="info-block">
            <h2>พี่น้อง</h2>
            @if (!empty($application->siblings))
                @foreach ($application->siblings as $sibling)
                    <p><strong>ชื่อ:</strong> {{ $sibling->PrefixName ?? 'N/A' }} {{ $sibling->Fname ?? 'N/A' }} {{ $sibling->Lname ?? 'N/A' }}</p>
                    <p><strong>อาชีพ:</strong> {{ $sibling->Occupation ?? 'N/A' }}</p>
                    <p><strong>ระดับการศึกษา:</strong> {{ $sibling->EducationLevel ?? 'N/A' }}</p>
                    <p><strong>รายได้:</strong> {{ $sibling->Income ?? 'N/A' }}</p>
                @endforeach
            @else
                <p>ไม่มีข้อมูลพี่น้อง</p>
            @endif
        </div>

        <div class="info-block">
            <h2>ผู้ปกครอง</h2>
            @if (!empty($application->guardians))
                @foreach ($application->guardians as $guardian)
                    <p><strong>ชื่อ:</strong> {{ $guardian->PrefixName ?? 'N/A' }} {{ $guardian->FirstName ?? 'N/A' }} {{ $guardian->LastName ?? 'N/A' }}</p>
                    <p><strong>อาชีพ:</strong> {{ $guardian->Occupation ?? 'N/A' }}</p>
                    <p><strong>รายได้:</strong> {{ $guardian->Income ?? 'N/A' }}</p>
                    <p><strong>สถานะ:</strong> {{ $guardian->Status ?? 'N/A' }}</p>
                @endforeach
            @else
                <p>ไม่มีข้อมูลผู้ปกครอง</p>
            @endif
        </div>

        <div class="info-block">
            <h2>ประวัติการได้รับทุน</h2>
            @if (!empty($application->scholarship_histories))
                @foreach ($application->scholarship_histories as $history)
                    <p><strong>ชื่อทุน:</strong> {{ $history->ScholarshipName ?? 'N/A' }}</p>
                    <p><strong>จำนวนเงินที่ได้รับ:</strong> {{ $history->AmountReceived ?? 'N/A' }}</p>
                    <p><strong>ปีการศึกษา:</strong> {{ $history->AcademicYear ?? 'N/A' }}</p>
                @endforeach
            @else
                <p>ไม่มีประวัติการได้รับทุน</p>
            @endif
        </div>

        <div class="info-block">
            <h2>กิจกรรม</h2>
            @if (!empty($application->activities))
                @foreach ($application->activities as $activity)
                    <p><strong>ชื่อกิจกรรม:</strong> {{ $activity->ActivityName ?? 'N/A' }}</p>
                    <p><strong>ตำแหน่ง:</strong> {{ $activity->Position ?? 'N/A' }}</p>
                @endforeach
            @else
                <p>ไม่มีข้อมูลกิจกรรม</p>
            @endif
        </div>

        <div class="info-block">
            <h2>ประสบการณ์การทำงาน</h2>
            @if (!empty($application->workExperiences))
                @foreach ($application->workExperiences as $experience)
                    <p><strong>ชื่อบริษัท:</strong> {{ $experience->Name ?? 'N/A' }}</p>
                    <p><strong>ประเภทงาน:</strong> {{ $experience->JobType ?? 'N/A' }}</p>
                    <p><strong>ระยะเวลา:</strong> {{ $experience->Duration ?? 'N/A' }}</p>
                    <p><strong>รายได้:</strong> {{ $experience->Earnings ?? 'N/A' }}</p>
                @endforeach
            @else
                <p>ไม่มีข้อมูลประสบการณ์การทำงาน</p>
            @endif
        </div>
    </div>
</body>
</html>
