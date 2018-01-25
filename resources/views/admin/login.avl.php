<html>
    <head>
        <title>Admin Login</title>
    </head>
    <body>
        <form action="{{ route('admin.do.login') }}" method="POST">
            <div><label>Email:</label></div>
			{!! csrf_field() !!}
            <div><input name="email"/></div>
            <div><label>Password:</label></div>
            <div><input type="password" name="password"/></div>
            <div><input type="submit"></div>
        </form>
    </body>
</html>