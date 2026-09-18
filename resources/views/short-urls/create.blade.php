<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Create Short URL</title></head>
<body>
<h1>Create Short URL</h1>
@if ($errors->any()) <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul> @endif
<form method="POST" action="{{ route('short-urls.store') }}">
@csrf
<div><label for="original_url">Original URL</label><input type="url" id="original_url" name="original_url" value="{{ old('original_url') }}" size="70" required></div><br>
<button type="submit">Create Short URL</button>
</form>
<p><a href="{{ route('short-urls.index') }}">Back to Short URLs</a></p>
</body></html>
