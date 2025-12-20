@csrf
<p>CSRF Token: {{ csrf_token() }}</p>
<p>Session ID: {{ session()->getId() }}</p>
