<script>
    (() => {
        const canonicalUrl = @json(rtrim((string) config('seo.site_url'), '/'));
        const localHosts = new Set(['localhost', '127.0.0.1', '::1']);

        if (! canonicalUrl || ! ['http:', 'https:'].includes(window.location.protocol)) {
            return;
        }

        const canonical = new URL(canonicalUrl);
        const currentHost = window.location.hostname.toLowerCase();
        const canonicalHost = canonical.hostname.toLowerCase();

        if (currentHost === canonicalHost || localHosts.has(currentHost)) {
            return;
        }

        window.location.replace(canonical.origin + window.location.pathname + window.location.search + window.location.hash);
    })();
</script>
