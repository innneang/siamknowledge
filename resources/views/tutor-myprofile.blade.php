    @extends('tutor-master')

    @section('title', 'Tutor Profile')
    @section('content')
    <div class="content-area">
    <form action='' method='post' enctype="multipart/form-data">
      <h1 class="heading">ข้อมูลของฉัน</h1><br>
      <h2 class="large-topic">ชื่อ - นามสกุล</h2>
      <input type="text" class="large-normal-input normal-input" name='name' value='{{$profile->name}}'>
      <h2 class="large-topic">ชื่อเล่น</h2>
      <input type="text" class="normal-input" name='calledname' value='{{$profile->calledname}}'>
      <h2 class="large-topic">ปีเกิด (ค.ศ.)</h2>
      <input type="number" class="number-spin-input" name='birthdate' value='{{$profile->birthdate}}'>
      <h2 class="large-topic">มหาวิทยาลัย</h2>
      <input type="text" class="large-normal-input normal-input" name='university' value='{{$profile->university}}'>
      <h2 class="large-topic">Bio</h2>
      <textarea type="text" class="large-normal-input normal-input" name='bio' >{{$profile->bio}}</textarea>
      <h1 class="sub-heading">ข้อมูลติดต่อ</h1><br>
      <h2 class="large-topic">E-mail</h2>
      <input type="text" class="normal-input" name='email' value='{{$profile->email}}'>
      <h2 class="large-topic">โทรศัพท์มือถือ</h2>
      <input type="text" class="normal-input" name='phone' value='{{$profile->phone}}'>
      <h1 class="sub-heading">รหัสผ่าน</h1><br>
      <h2 class="large-topic">เปลี่ยนรหัสผ่าน</h2>
      <input placeholder='รหัสผ่านเก่า' type="password" class="normal-input" name='oldpass'>
      <input placeholder='รหัสผ่านใหม่' type="password" class="normal-input" name='password'>
      <input placeholder='ยืนยันรหัสผ่านใหม่' type="password" class="normal-input" name='password_confirmation'>
      <h2 class="large-topic">อัพโหลดภาพประจำตัวใหม่</h2>
      @if(count($errors->get('avatar')) > 0)<br><span class="ion-ios-minus-outline"></span>ไฟล์ไม่ใช่รูปภาพหรือมีขนาดเกิน 2mb</span></h3>@endif
      <!--<input type="file" class="normal-input" name='avatar'>-->
      <br/>

            <input type="file" id="files" name='avatar' >



      <br/>      <br/>
      <input type="hidden" name="_method" value="PUT">
      {{ csrf_field() }}
   <button type='submit' class="button button-orange">บันทึกการแก้ไข</button>
   </form>
    </div>
    @endsection
    @section('postscript')
  @if(count($errors->all())>0)
    <script type="text/javascript">swal('มีข้อผิดพลาด กรุณาตรวจสอบข้อมูลอีกครั้ง');</script>
  @endif
  @if (session('status'))
    <script type="text/javascript">swal('{{ session('status') }}' );</script>
  @endif
@endsection
