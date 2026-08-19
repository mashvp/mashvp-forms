# Mashvp – Forms

No-bullsh!t form plugin

**⏸️ Development state is now in indefinite maintenance**  
**🛑 New uses of this plugin are discouraged**

### ⚠️ Important

If you use a cache plugin, or another cache solution such as Redis, Varnish or CloudFlare :

Make sure your cache is set to expire in less than the validity duration of WordPress nonces (by default, 12h). If not, your forms will stop working after the nonce field expires.

> ℹ️  
> You can now sidetep this issue by using version `^0.5`, which introduces nonce refresh on submit for forms using the AJAX mode.
