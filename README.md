# Mashvp – Forms

No-bullsh!t form plugin

**This plugin is in beta, be careful before using it in production.**

### ⚠ Important

If you use a cache plugin, or another cache solution such as Redis, Varnish or CloudFlare :

Make sure your cache is set to expire in less than the validity duration of WordPress Nonces (by default, 12h). If not, your forms will stop working after the nonce field expires.
