# Form types

This bundle has 2 build-in form types:
- `SiteType`
- `IdentifiedSiteType`

Both types extend Symfony's `ChoiceType`.
The choices are loaded from your implementation of `SiteRepositoryInterface`.

In short, you can use it to select one or multiple sites by a form widget.
