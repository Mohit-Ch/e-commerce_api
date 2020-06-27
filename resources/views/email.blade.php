
@if($status==1)
<!DOCTYPE html>
<html>
<head>
	<title>Its in {!! $username !!}</title>
</head>
<body>
	<div>{!! $message1 !!}</div>
<br/>
<br/>
<br/>
	<div> Thanks and Regards<div>
	<div> {!! $username !!}<div>
	<div> {!! $email !!}<div>
	<div> {!! $phone !!}<div>
</body>
</html>
@endif
@if($status==2)

<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>
</head>
<body>
	<div> Hi {{$name}}</div>
	<div> We have Generated new password can you Plesase login to new password</div>
	<div>Please login using your temporary password: <b> {{$password}} </b> </div>
<br/>
<br/>
<br/>
	<div> Thanks and Regards<div>
	<div> Golden Handle<div>
	
</body>
</html>
@endif
