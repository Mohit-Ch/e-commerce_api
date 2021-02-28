
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
	<div> Jain Hardware<div>
	
</body>
</html>
@endif

@if($status==3)

<!DOCTYPE html>
<html>
<head>
	<title>Order Details</title>
</head>
<body>
	<div> Hi {{$name}}</div>
	<div> You have Placed an order {{$orderdetail["order_no"]}} </div>
<div > 
  <div class="table-responsive">
    <table class="table align-items-center table-flush">
      <thead class="thead-light">
        <tr>
          <th scope="col">Image</th>
          <th scope="col">Item Detail</th>
          <th scope="col">Quantity</th>
          <th scope="col">price</th>
        </tr>
      </thead>
      <tbody >
	  @foreach($orderdetail["itemList"] as  $item)
        <tr>
          <th scope="row">
            <img src={{$item["imageURL"]}} width="40px">
          </th>
          <td>
            {{$item["itemName"]}},<br/> {{$item["itemEditionName"]}}
          </td>
          <td>
            {{$item["quantity"]}}
          </td>
          <td>
            {{$item["price"]}}
          </td>         
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-5">
  <table>
  <tr>
  <td>SubTotal -</td>
  <td> {{$orderdetail["actual_amount"]}}</td>
  </tr>
  <tr>
  <td>  Discount -</td>
  <td>{{$orderdetail["discount"] }}</td>
  </tr>
  <tr>
  <td>  Total -</td>
  <td>{{$orderdetail["product_amount"]}}</td>
  </tr>
  </table>    
  </div>
  
</div>
<br/>
<br/>
<br/>
	<div> Thanks and Regards<div>
	<div> Jain Hardware<div>
	
</body>
</html>
@endif


