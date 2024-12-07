<h1>Hello {{ $post->user->name }},</h1>
<p>A new comment has been added to your post titled "{{ $post->title }}" by {{ $comment['author'] }}.</p>
<p>{{ $comment['comment'] }}</p>
