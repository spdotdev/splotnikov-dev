{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en">
  <title>Notes from my own desk | Stanislav Plotnikov</title>
  <subtitle>Technology and what I am busy with: PHP, Laravel, AI agents, security and Linux, from my own experience.</subtitle>
  <link rel="self" type="application/atom+xml" href="https://splotnikov.dev/feed.xml" />
  <link rel="alternate" type="text/html" href="https://splotnikov.dev/blog" />
  <id>https://splotnikov.dev/blog</id>
  <updated>{{ $updated }}</updated>
  <author><name>Stanislav Plotnikov</name><uri>https://splotnikov.dev</uri></author>
@foreach($entries as $e)
  <entry>
    <title type="text">{{ $e['title'] }}</title>
    <link rel="alternate" type="text/html" href="{{ $e['url'] }}" />
    <id>{{ $e['url'] }}</id>
    <updated>{{ $e['updated'] }}</updated>
    <summary type="text">{{ $e['summary'] }}</summary>
  </entry>
@endforeach
</feed>
