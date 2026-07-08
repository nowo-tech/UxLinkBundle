# Code inventory — 100% traceability

**Baseline spec**: [`spec.md`](spec.md)  
**Package**: `nowo-tech/ux-link-bundle`  
**Last audited**: 2026-07-07

This file proves that **every production source artifact** under `src/` is referenced by the baseline specification. Test-only files under `tests/` and `*.test.ts` under `src/` are out of Packagist scope. Built assets under `Resources/public/` are documented as Vite/build outputs.

## Bundle & DI

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Config/BundleConfiguration.php` | Runtime configuration | FR-CFG-003 |
| `DependencyInjection/Compiler/TwigPathsPass.php` | Compiler pass | FR-DI-002 |
| `DependencyInjection/Configuration.php` | Config tree | FR-CFG-001 |
| `DependencyInjection/NowoUxLinkExtension.php` | DI extension | FR-CFG-002 |
| `NowoUxLinkBundle.php` | Bundle entry | FR-BUNDLE-001 |

## Domain models

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Enum/ExternalTargetPolicy.php` | Domain enum | FR-MDL-001 |
| `Enum/LinkAvailability.php` | Domain enum | FR-MDL-001 |
| `Enum/LinkFamily.php` | Domain enum | FR-MDL-001 |
| `Enum/MapAction.php` | Domain enum | FR-MDL-001 |
| `Model/Link.php` | Domain model | FR-MDL-002 |
| `Model/LinkAttributes.php` | Domain model | FR-MDL-002 |
| `Model/LinkCollection.php` | Domain model | FR-MDL-002 |
| `Model/LinkRenderOptions.php` | Domain model | FR-MDL-002 |
| `Model/Options/ContactOptions.php` | Domain model | FR-MDL-002 |
| `Model/Options/DownloadOptions.php` | Domain model | FR-MDL-002 |
| `Model/Options/LinkOptionsInterface.php` | Domain model | FR-MDL-002 |
| `Model/Options/MapOptions.php` | Domain model | FR-MDL-002 |
| `Model/Options/ShareOptions.php` | Domain model | FR-MDL-002 |

## Security

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Security/HtmlAttributePolicy.php` | URL/HTML policy | FR-SEC-004 |
| `Security/UrlPolicy.php` | URL/HTML policy | FR-SEC-004 |

## Link providers

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Provider/AbstractLinkProvider.php` | Generic link provider | FR-PROV-001 |
| `Provider/Contact/EmailContactProvider.php` | Contact link provider | FR-PROV-001 |
| `Provider/Contact/SmsContactProvider.php` | Contact link provider | FR-PROV-001 |
| `Provider/Contact/TelephoneContactProvider.php` | Contact link provider | FR-PROV-001 |
| `Provider/Contact/WhatsappContactProvider.php` | Contact link provider | FR-PROV-001 |
| `Provider/Download/GenericDownloadProvider.php` | Download link provider | FR-PROV-001 |
| `Provider/Map/AppleMapsProvider.php` | Map link provider | FR-PROV-001 |
| `Provider/Map/GoogleMapsProvider.php` | Map link provider | FR-PROV-001 |
| `Provider/Map/OpenStreetMapProvider.php` | Map link provider | FR-PROV-001 |
| `Provider/Map/WazeMapProvider.php` | Map link provider | FR-PROV-001 |
| `Provider/Share/EmailShareProvider.php` | Share link provider | FR-PROV-001 |
| `Provider/Share/LinkedInShareProvider.php` | Share link provider | FR-PROV-001 |
| `Provider/Share/TelegramShareProvider.php` | Share link provider | FR-PROV-001 |
| `Provider/Share/WhatsappShareProvider.php` | Share link provider | FR-PROV-001 |
| `Provider/Share/XShareProvider.php` | Share link provider | FR-PROV-001 |

## Contracts & attributes

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Attribute/AsLinkProvider.php` | Public contract | FR-API-001 |
| `Contract/IconResolverInterface.php` | Public contract | FR-API-001 |
| `Contract/LinkFactoryInterface.php` | Public contract | FR-API-001 |
| `Contract/LinkProviderInterface.php` | Public contract | FR-API-001 |
| `Contract/LinkRendererInterface.php` | Public contract | FR-API-001 |

## Rendering

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Factory/LinkFactory.php` | Link factory/registry | FR-FACT-001 |
| `Factory/OptionsFactory.php` | Link factory/registry | FR-FACT-001 |
| `Registry/LinkProviderRegistry.php` | Link factory/registry | FR-FACT-001 |
| `Renderer/DefaultIconResolver.php` | Link renderer | FR-RENDER-001 |
| `Renderer/HtmlLinkRenderer.php` | Link renderer | FR-RENDER-001 |
| `Renderer/UrlRenderer.php` | Link renderer | FR-RENDER-001 |

## Twig PHP

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Twig/Component/UxDownloadLink.php` | Twig UX component | FR-TWIG-002 |
| `Twig/Component/UxLink.php` | Twig UX component | FR-TWIG-002 |
| `Twig/Component/UxLinks.php` | Twig UX component | FR-TWIG-002 |
| `Twig/Component/UxShareLinks.php` | Twig UX component | FR-TWIG-002 |
| `Twig/UxLinkExtension.php` | Twig extension | FR-TWIG-001 |

## Support utilities

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Util/PhoneNormalizer.php` | Support utility | FR-UTIL-001 |
| `Util/UrlBuilder.php` | Support utility | FR-UTIL-001 |

## Exceptions

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Exception/DisabledProviderException.php` | Domain exception | FR-ERR-001 |
| `Exception/InvalidLinkOptionsException.php` | Domain exception | FR-ERR-001 |
| `Exception/InvalidUrlException.php` | Domain exception | FR-ERR-001 |
| `Exception/ProviderNotFoundException.php` | Domain exception | FR-ERR-001 |

## Symfony config

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Resources/config/services.php` | Service wiring | FR-DI-001 |

## Translations

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Resources/translations/NowoUxLinkBundle.de.yaml` | i18n messages | FR-I18N-004 |
| `Resources/translations/NowoUxLinkBundle.en.yaml` | i18n messages | FR-I18N-004 |
| `Resources/translations/NowoUxLinkBundle.es.yaml` | i18n messages | FR-I18N-004 |
| `Resources/translations/NowoUxLinkBundle.fr.yaml` | i18n messages | FR-I18N-004 |
| `Resources/translations/NowoUxLinkBundle.it.yaml` | i18n messages | FR-I18N-004 |
| `Resources/translations/NowoUxLinkBundle.nl.yaml` | i18n messages | FR-I18N-004 |
| `Resources/translations/NowoUxLinkBundle.pt.yaml` | i18n messages | FR-I18N-004 |

## Twig views

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `Resources/views/components/ux-download-link.html.twig` | Twig component template | FR-VIEW-008 |
| `Resources/views/components/ux-link-component.html.twig` | Twig component template | FR-VIEW-008 |
| `Resources/views/components/ux-link.html.twig` | Twig component template | FR-VIEW-008 |
| `Resources/views/components/ux-links.html.twig` | Twig component template | FR-VIEW-008 |
| `Resources/views/components/ux-share-links.html.twig` | Twig component template | FR-VIEW-008 |

## Coverage summary

| Category | Files | Mapped |
| --- | ---: | ---: |
| Bundle & DI | 5 | 5 |
| Domain models | 13 | 13 |
| Security | 2 | 2 |
| Link providers | 15 | 15 |
| Contracts & attributes | 5 | 5 |
| Rendering | 6 | 6 |
| Twig PHP | 5 | 5 |
| Support utilities | 2 | 2 |
| Exceptions | 4 | 4 |
| Symfony config | 1 | 1 |
| Translations | 7 | 7 |
| Twig views | 5 | 5 |
| **Total production sources** | **70** | **70** |

Audit: `find src -type f ! -path '*/assets/dist/*' ! -name '*.test.ts' | wc -l`
