{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($sections as $section)
  <sitemap>
    <loc>{{ $section['loc'] }}</loc>
    @if(!empty($section['lastmod']))
    <lastmod>{{ $section['lastmod']->toAtomString() }}</lastmod>
    @endif
  </sitemap>
@endforeach
</sitemapindex>
