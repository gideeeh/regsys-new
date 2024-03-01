@extends('admin.appointments')
@section('content')
<h1>Appointments Dashboard</h1>
<div>
    <div>
        <button>Today</button>
        <button>This Week</button>
        <button>This Month</button>
    </div>
    <select id="stu-appointment" name="stu-appointment" style="width: 100%;"></select>
</div>

<script>
    var getSubjectsUrl = "{{ url('/admin/functions/get-subjects') }}";
    var getStudentsUrl = "{{ url('/admin/students/get-students') }}";
    var fetchSection = "{{url('/sections/fetch')}}"
    var searchSecSub = "{{url('/admin/functions/get-section-subjects')}}" 
    // Any other variables
</script>
<script src="{{ asset('js/appointments.js') }}"></script>

@endsection