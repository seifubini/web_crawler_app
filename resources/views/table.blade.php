<!DOCTYPE html>
<html lang="en">
<head>
  <title>Phone Numbers Table</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2> Phone Numbers Listed</h2>
  <table class="table">
    <thead>
      <tr>
        <th>No</th>
        <th>Phone Number</th>
      </tr>
    </thead>
    <tbody>
    @if(!empty($results) && $results->count())
    @php $i = 0; @endphp
    @foreach($results as $contact)
      <tr>
        <td>{{++$i}}</td>
        <td>{{$contact->content}}</td>
      </tr>
    @endforeach
    @else
      <tr>
          <td colspan="2">There are no record. Crawl now <a href="{{ url('/crawl') }}" style="text-decoration: none;">Crawl</a></td>
      </tr>
    @endif
    </tbody>
  </table>
  {!! $results->links() !!}
</div>

</body>
</html>