# ChangeLog
This document is meant for tracking notable changes to `nova-chartjs`

## 0.1.0 
- Initial Release

## 0.2.0 
- Fixed title underscore issue
- Using proper title based on plurality
- Github actions for PHPUnit and PHPCS Fixer
- Visibility and can methods working
- Added `requireChild()` method to force the creation of a child relationship
- Ensure parent model updates properly in `BelongsTo` relationship. Thanks to [@mahentoo](https://github.com/mahentoo) [#29](https://github.com/kirschbaum-development/nova-inline-select/pull/29).
- Inline relationships within `Panel` now works. Thanks to [@timvanuum](https://github.com/timvanuum) [#24](https://github.com/kirschbaum-development/nova-inline-select/pull/24).
- Check if value is instance of model or collection with proper model key. Thanks to [@gabrielkoerich](https://github.com/gabrielkoerich) [#18](https://github.com/kirschbaum-development/nova-inline-select/pull/18).

## 0.3.0
- Third-party integration contract
- Fixed datetime display [#46](https://github.com/kirschbaum-development/nova-inline-relationship/issues/46)
- Updated singularLabel display [#45](https://github.com/kirschbaum-development/nova-inline-relationship/issues/45)
- Use column based ordering for reordering via drag and drop [#60](https://github.com/kirschbaum-development/nova-inline-relationship/issues/60)
