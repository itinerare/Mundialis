<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [3.1.0](https://code.itinerare.net/itinerare/Mundialis/compare/v3.0.0...v3.1.0) (2025-06-02)

### Features


##### Factions

* Add factions as subject ([4e3310](https://code.itinerare.net/itinerare/Mundialis/commit/4e3310eccd4affbdb73fc73744cc6b976a0967ba))

##### Tests

* Extend subject support to additional tests ([d63a65](https://code.itinerare.net/itinerare/Mundialis/commit/d63a659129667d657393afd1d3c1d6f4ee0cf891))

### Bug Fixes


##### Pages

* Better validate parent objects ([f1255a](https://code.itinerare.net/itinerare/Mundialis/commit/f1255a35f91208872fdd84ae67882554e6fda70d))


---

## [3.0.0](https://code.itinerare.net/itinerare/Mundialis/compare/v2.6.1...v3.0.0) (2025-05-26)

### ⚠ BREAKING CHANGES

* Rebuild on Laravel 11 application skeleton ([685eb7](https://code.itinerare.net/itinerare/Mundialis/commit/685eb784ca8f964ba0ae985f63bc31a1a9a656d2))
* Update to Laravel 11 ([ef26e8](https://code.itinerare.net/itinerare/Mundialis/commit/ef26e889f8c042044b490078d7ddf04f7db454d0))

### Features

* Enable automatic eager loading ([469b0f](https://code.itinerare.net/itinerare/Mundialis/commit/469b0f1d316f06fcce9d6ea430b34dead069f82f))

### Bug Fixes

* Adjust form syntax ([7e5921](https://code.itinerare.net/itinerare/Mundialis/commit/7e59212989d1144290a66133e6188c768a3b182d))
* Correct page, lexicon entry create/edit form urls ([2d0779](https://code.itinerare.net/itinerare/Mundialis/commit/2d0779264d6c1019ccf6d83bb2964e463fc0ce7d))
* Don't handle wiki link parsing in a transaction ([d07385](https://code.itinerare.net/itinerare/Mundialis/commit/d07385796fdd2a6e916428c66848850eb74375eb))
* Finesse landing page display ([22f407](https://code.itinerare.net/itinerare/Mundialis/commit/22f407a323715566d143a5bb02e613eb69821e96))
* Revert example .env session driver value ([b869b1](https://code.itinerare.net/itinerare/Mundialis/commit/b869b13d861a4e714850cc8720b8073b9aa9d36e))
* Update example .env ([ab0867](https://code.itinerare.net/itinerare/Mundialis/commit/ab0867e5762782304c242d9f46d09975e3dccc87))
* Update more form opens; closes #492 ([f52ff1](https://code.itinerare.net/itinerare/Mundialis/commit/f52ff1b8b60bd0ea09381cbbc8550c7aab05fb73))
* Use polymorphic relationships for page links ([8404c5](https://code.itinerare.net/itinerare/Mundialis/commit/8404c5a774474f8225e744eca7b62d542574486f))

##### Pages

* Allow more unicode characters in page links; closes #496 ([bdc774](https://code.itinerare.net/itinerare/Mundialis/commit/bdc774d5abdef4808a05f03ad0bec13ad3cfa96f))
* Create a new version when updating wanted page links ([37530a](https://code.itinerare.net/itinerare/Mundialis/commit/37530a688e4d53296e18dcbfec11a45a4ccdd396))
* Decode HTML entities when parsing page links; closes #493 ([a3c567](https://code.itinerare.net/itinerare/Mundialis/commit/a3c56719f1357a47c04ba44eb0fafb58e3fa9270))
* Provide dateHelper on move page view for people, time pages ([7d40ee](https://code.itinerare.net/itinerare/Mundialis/commit/7d40ee3925749426c3e2188ada618fbaf5bea96d))
* Save version data when updating wanted page links; closes #494 ([cbcf4d](https://code.itinerare.net/itinerare/Mundialis/commit/cbcf4dba435d711a8368110d38496353ac86be0c))

##### Special Pages

* Check for link parent existence when viewing link related special pages ([925143](https://code.itinerare.net/itinerare/Mundialis/commit/9251431e23f7d2682b3f7a40dfa0ea7c598fad4f))

##### Subjects

* Improve page terminology on subject page ([44fbb9](https://code.itinerare.net/itinerare/Mundialis/commit/44fbb962a83328d11f1b97b6f066c37e123fd354))

##### Tests

* Remove unnecessary DB cleanup from some test files ([c2c4e5](https://code.itinerare.net/itinerare/Mundialis/commit/c2c4e5309138766265bf846976c3c75b7d87f5c7))
* Update PHPUnit, tests ([915125](https://code.itinerare.net/itinerare/Mundialis/commit/9151255a75451c157b39fbdd4a08e0e59074df11))
* Use LazilyRefreshDatabase on base test case ([045ebb](https://code.itinerare.net/itinerare/Mundialis/commit/045ebb85f257fe7c7244d2a251437cbb89159253))

##### Users

* Refine forgot password message check ([35dc82](https://code.itinerare.net/itinerare/Mundialis/commit/35dc827e45a4efee711160ff7a132de24e53e7b5))


---

## [2.6.1](https://code.itinerare.net/itinerare/Mundialis/compare/v2.6.0...v2.6.1) (2025-03-17)

### Bug Fixes


##### Pages

* Always check for page when collecting context tag pages; closes #491 ([862624](https://code.itinerare.net/itinerare/Mundialis/commit/86262411007f3063e541773cec8cb6e339db5690))
* Eager load basic page properties with tags ([ce54c8](https://code.itinerare.net/itinerare/Mundialis/commit/ce54c81fa091f630c6e168dd3df404f1d1b26671))
* Eager load common page relations on tag index ([601023](https://code.itinerare.net/itinerare/Mundialis/commit/601023fa54d38d893f3232b574c463afe003a300))
* Filter deleted pages when checking if tag has a navbox ([d208d7](https://code.itinerare.net/itinerare/Mundialis/commit/d208d7350d1f9ab0b7a556afc13b00b463128e6b))
* Include deleted_at when eager loading tag pages ([192ecf](https://code.itinerare.net/itinerare/Mundialis/commit/192ecf403d7aa748082c5dae21abfefcb9759514))

##### Subjects

* Eager load parent in admin subject category index ([d521bc](https://code.itinerare.net/itinerare/Mundialis/commit/d521bc8d45bef584326eea7ab7eaac2778d9c703))
* Paginate subcategories on category pages separately; closes #490 ([e77166](https://code.itinerare.net/itinerare/Mundialis/commit/e77166f0e8445a4fb03c1d157b308e4e1ff2d11b))


---

## [2.6.0](https://code.itinerare.net/itinerare/Mundialis/compare/v2.5.1...v2.6.0) (2025-01-13)

### Features


##### Tests

* Add visibility, validity checks to page image sort tests ([2ce1d8](https://code.itinerare.net/itinerare/Mundialis/commit/2ce1d813458e08d91613488bd350b0c60d5373de))
* Add visibility, validity to get page image sort tests ([25ca48](https://code.itinerare.net/itinerare/Mundialis/commit/25ca481e1c23dbe1ca343a0fa407694968775251))

### Bug Fixes

* Use full ValidateSignature middleware path ([99ffa6](https://code.itinerare.net/itinerare/Mundialis/commit/99ffa677e706554728994972a7afaaf158f7486d))


---

## [2.5.1](https://code.itinerare.net/itinerare/Mundialis/compare/v2.5.0...v2.5.1) (2024-12-30)

### Bug Fixes


##### Page Images

* Remove visibility checks when sorting images ([c1a966](https://code.itinerare.net/itinerare/Mundialis/commit/c1a966b3d554b2f322fda02beec0f7e30c8c6af7))


---

## [2.5.0](https://code.itinerare.net/itinerare/Mundialis/compare/v2.4.0...v2.5.0) (2024-12-16)

### Features


##### Page Images

* Add ability to reorder page images; closes #489 ([3dc3f6](https://code.itinerare.net/itinerare/Mundialis/commit/3dc3f6c67f2796c59ff566547d45453f49c36df0))

##### Tests

* Add support for sorting to page image tests ([b091a9](https://code.itinerare.net/itinerare/Mundialis/commit/b091a98aa142ff63c45aefebd83752c042cd2460))


---

## [2.4.0](https://code.itinerare.net/itinerare/Mundialis/compare/v2.3.0...v2.4.0) (2024-10-13)

### Features

* Add error pages ([91d9cb](https://code.itinerare.net/itinerare/Mundialis/commit/91d9cbcf60eac76f55646e8d727de6dfafcc1128))


---

## [2.3.0](https://code.itinerare.net/itinerare/Mundialis/compare/v2.2.6...v2.3.0) (2024-06-09)

### Features

* Allow cascade on subject template creation ([c7a1dc](https://code.itinerare.net/itinerare/Mundialis/commit/c7a1dc0bd6de43cbf2d0b4682b7a8bfe024d72e8))
* Enable model safety features ([6d14f3](https://code.itinerare.net/itinerare/Mundialis/commit/6d14f3edd8708bbfd95c0df1825082241a7823bd))
* Update to Laravel 10 ([4aaddb](https://code.itinerare.net/itinerare/Mundialis/commit/4aaddbc19726b1e6dc4e19272ce9bb62218cc9fb))

##### Page Images

* Add WebP, JPG to accepted formats ([be112f](https://code.itinerare.net/itinerare/Mundialis/commit/be112f9fac023b0c5a3e13b35f0810b8ea2f8743))

##### Pages

* Add unlinked pages special page ([cb0756](https://code.itinerare.net/itinerare/Mundialis/commit/cb0756b16640f2a825d7e7a85e4a62165513bbb7))
* Indicate optional template fields ([3fe74c](https://code.itinerare.net/itinerare/Mundialis/commit/3fe74c6be025a235fa8c48e9d7f4c3cb7eb8a3d5))

##### Tests

* Add bool sequence function to TestCase ([051666](https://code.itinerare.net/itinerare/Mundialis/commit/051666cad6b879ffdff23c5d774edf1a03dede0a))
* Add cleanup option to test image function ([bb9155](https://code.itinerare.net/itinerare/Mundialis/commit/bb9155360812b84fb7d9ec6bf119572b5620998c))
* Add invalid to subject view test ([b94099](https://code.itinerare.net/itinerare/Mundialis/commit/b94099af2d67efd8fdb98bcc0ef1fad8735b5397))
* Add page reset tests ([173919](https://code.itinerare.net/itinerare/Mundialis/commit/1739199dc26e4520425c34c276ffda5258a27b33))
* Add service errors to Laravel session errors ([878f8c](https://code.itinerare.net/itinerare/Mundialis/commit/878f8cfc25f737a35f9d73b9342f44dadd722862))
* Add unrelated category check to subject view tests ([6871aa](https://code.itinerare.net/itinerare/Mundialis/commit/6871aa409234b8582fbee3d9ccbc2560b78c3a41))
* Add visibility checks to page view tests ([d4ff6a](https://code.itinerare.net/itinerare/Mundialis/commit/d4ff6aa3739e643c304df16b8cabdc973c4d5d82))
* Perform basic site setup in TestCase ([98af6b](https://code.itinerare.net/itinerare/Mundialis/commit/98af6bf9f5f1315bdad721031b71a43eecf2cd5e))
* Return gallery test to page view tests ([271081](https://code.itinerare.net/itinerare/Mundialis/commit/271081e2dd5f4045897d479407e5ceeae0936d1e))
* Update access tests ([c9cb91](https://code.itinerare.net/itinerare/Mundialis/commit/c9cb91c8f684ec7d0fc190e21acae5952067a135), [3ec72c](https://code.itinerare.net/itinerare/Mundialis/commit/3ec72c31970d11d8aaf8ebecd631b6504733f1b7))
* Update admin invitation tests ([3cb05d](https://code.itinerare.net/itinerare/Mundialis/commit/3cb05d497bb791bcf6a0f512a46326cf731b5610))
* Update admin rank tests ([e7fef1](https://code.itinerare.net/itinerare/Mundialis/commit/e7fef125374cda82c4abfa05c3b0463ad822eeac))
* Update admin site images tests ([e21390](https://code.itinerare.net/itinerare/Mundialis/commit/e213906bf007e61b3a8436dc513f20c6d6265eba))
* Update admin site settings tests ([404834](https://code.itinerare.net/itinerare/Mundialis/commit/404834ac6b1a3ed2534551b249689e5ba95a8039))
* Update admin user edit/ban tests ([706f64](https://code.itinerare.net/itinerare/Mundialis/commit/706f64ad7378ceb82ad4b5a73cc9d529f229455e))
* Update auth password reset tests ([0e0ac0](https://code.itinerare.net/itinerare/Mundialis/commit/0e0ac0f7d38056a6db3079ec13cb7447c7c06172))
* Update auth registration tests ([6b530f](https://code.itinerare.net/itinerare/Mundialis/commit/6b530f3a31c8476ca4b3cb34d0546689a5711ee8))
* Update get deleted pages tests, move to page delete tests ([253a7a](https://code.itinerare.net/itinerare/Mundialis/commit/253a7af1c446f3f5a8497e5ee1e574cd13980915))
* Update lexicon entry tests, factories ([f1f3af](https://code.itinerare.net/itinerare/Mundialis/commit/f1f3af807d6d8befef683906073ebb74bb113a33))
* Update page create tests ([6e97c3](https://code.itinerare.net/itinerare/Mundialis/commit/6e97c35418e7500fbb83d101e907a41dabc08f3b))
* Update page delete tests ([aa286c](https://code.itinerare.net/itinerare/Mundialis/commit/aa286c43f3bd44652242061f9b852c62dc28a276))
* Update page edit field tests ([ba6fae](https://code.itinerare.net/itinerare/Mundialis/commit/ba6fae9f5c455ecdb2aba248fae8fb428ad2427b))
* Update page edit tests ([a1615e](https://code.itinerare.net/itinerare/Mundialis/commit/a1615ef0e089e6c5c1f39b7dd464957e1ca458dd))
* Update page image delete tests ([460e39](https://code.itinerare.net/itinerare/Mundialis/commit/460e397c292337d199ec505fda1ed3f047b80c06))
* Update page image edit tests ([694a2e](https://code.itinerare.net/itinerare/Mundialis/commit/694a2e2195ebfc4f2876560f3c0d78c36ff487b4))
* Update page image view tests ([35e7fb](https://code.itinerare.net/itinerare/Mundialis/commit/35e7fb34da9f9763415886d1e8a7f48fd2727cc5))
* Update page link tests ([2e4126](https://code.itinerare.net/itinerare/Mundialis/commit/2e412687fca3bc82019136731209c68937b81dac))
* Update page move tests ([f723bb](https://code.itinerare.net/itinerare/Mundialis/commit/f723bb01d42268f27c8bea46dd8634b05c0ad969))
* Update page protect tests ([9c2715](https://code.itinerare.net/itinerare/Mundialis/commit/9c27154950051ad8e2e66cfeaf5abba28455f939))
* Update page relationship tests ([5d95b7](https://code.itinerare.net/itinerare/Mundialis/commit/5d95b774aa75804e0c8fe87677f1d65bd4a18deb))
* Update page tag tests ([43decd](https://code.itinerare.net/itinerare/Mundialis/commit/43decdd5f579dfd3af8365a3b68747dc296d6c2e))
* Update page time tests ([a068dc](https://code.itinerare.net/itinerare/Mundialis/commit/a068dce4f2c62d3fbc7f68a599f9b7ddd3571ce2))
* Update page view field tests ([638e3a](https://code.itinerare.net/itinerare/Mundialis/commit/638e3a263d3c0006dd9aa52075d002003b99fa57))
* Update page view tests ([ee943d](https://code.itinerare.net/itinerare/Mundialis/commit/ee943dc082018adb5c8f6fb080f7d888b8bae1fd))
* Update page watch tests ([f76f16](https://code.itinerare.net/itinerare/Mundialis/commit/f76f1647c94ae932f01836e727444ea5dc6ede5e))
* Update special page tests ([19e0da](https://code.itinerare.net/itinerare/Mundialis/commit/19e0dafcd74187357300df5ce2a838329fd7e61c))
* Update subject data category tests ([d55c32](https://code.itinerare.net/itinerare/Mundialis/commit/d55c32bb332225e683adcde0297d93d77d09d1dd))
* Update subject data field tests ([a052ce](https://code.itinerare.net/itinerare/Mundialis/commit/a052ceb8b00e16c4ebec246382b0272a618f8d59))
* Update subject data language tests ([b32532](https://code.itinerare.net/itinerare/Mundialis/commit/b325327c9f57591c3bbb98734c70d8d8ed11fd71))
* Update subject data time tests ([12694a](https://code.itinerare.net/itinerare/Mundialis/commit/12694a9f0fb91c520261b710d5e1623176dbe41d))
* Update subject template tests, add factory ([e09fb3](https://code.itinerare.net/itinerare/Mundialis/commit/e09fb32af38a18b3ac627130fe4e9777bde4a766))
* Update subject view tests ([729a1e](https://code.itinerare.net/itinerare/Mundialis/commit/729a1e9be7b232be65f3256b45fefc2fd18c6a2b))
* Update update admin site page tests ([5ba45d](https://code.itinerare.net/itinerare/Mundialis/commit/5ba45d6289ae07fe377681f53b9ddf17fef3889c))
* Update user function tests ([f4acfe](https://code.itinerare.net/itinerare/Mundialis/commit/f4acfe2bbf8ef3f01aa082c4ad87f36352628723))
* Update user notification tests ([f2a822](https://code.itinerare.net/itinerare/Mundialis/commit/f2a822dedf098c634fbefea9660f1c672bd68315))

### Bug Fixes

* Allow clearing category data ([e4a2e1](https://code.itinerare.net/itinerare/Mundialis/commit/e4a2e1b4976eeebf5dacd2b3bff95d76745312da))
* Do not require template field default value to be a string ([0c524a](https://code.itinerare.net/itinerare/Mundialis/commit/0c524a630d01480a648af27a82536e7f2b11a08c))
* Do not silently discard attributes when creating/editing subject categories ([506d8b](https://code.itinerare.net/itinerare/Mundialis/commit/506d8bdb6cd2a2f3fb0caa6e14feaa2d82da3aba))
* Do not silently discard attributes when editing subject templates ([f7ec11](https://code.itinerare.net/itinerare/Mundialis/commit/f7ec115269229942c10714d3da56cf69ecc524de))
* Eager load images, relationships when force deleting pages ([acc288](https://code.itinerare.net/itinerare/Mundialis/commit/acc288eba1daa54f765fa9a98080abc3a743f9d2))
* Eager load page/page image details on index page ([b55cbe](https://code.itinerare.net/itinerare/Mundialis/commit/b55cbeb2b052b0761572c61fad866004b0f54ded))
* Eager load tag pages for all tags page ([ece7f7](https://code.itinerare.net/itinerare/Mundialis/commit/ece7f706bf7167d80275cb13d13d6894cea5323f))
* Eager load various page, etc relationships for special pages ([cf8bd2](https://code.itinerare.net/itinerare/Mundialis/commit/cf8bd26fab7d8853285a0cdbc3ae9dc567a38bb7))
* Improve checks for category existence when deleting ([6a1a27](https://code.itinerare.net/itinerare/Mundialis/commit/6a1a2748bfc4a0764780ca82a9594b42892b3018))
* Make category image field nullable ([b805c0](https://code.itinerare.net/itinerare/Mundialis/commit/b805c0502c460f4e5e1162e9c857f515b3688efb))
* Update middleware ([6b4e97](https://code.itinerare.net/itinerare/Mundialis/commit/6b4e9764a4d3c4d74fc626aa63275468a230d9e5))
* Validate field type when editing templates ([529f19](https://code.itinerare.net/itinerare/Mundialis/commit/529f19edcaa1bff0827cccd15681b1ae3b432b08))

##### Invitations

* Always eager load user/recipient ([9644ed](https://code.itinerare.net/itinerare/Mundialis/commit/9644ed8d6952a8e90af883d7927da108a65f564e))
* Eager load user rank IDs ([f88914](https://code.itinerare.net/itinerare/Mundialis/commit/f88914fe5889d4bf0559839dab709cab41fcd5cb))

##### Lang

* Autoconj ignores if word is being changed ([10b44f](https://code.itinerare.net/itinerare/Mundialis/commit/10b44f2f2208089c25c3b46e2bd75ff742b9e61b))
* Fix filtering lexicon entries by part of speech ([b3b73f](https://code.itinerare.net/itinerare/Mundialis/commit/b3b73f88d6c608cf232690c99460b098c206ee34))
* Fix filtering lexicon entries by pronunciation ([061bee](https://code.itinerare.net/itinerare/Mundialis/commit/061beeede9b7d1bc0b5e6d404540c8c65e571ba6))
* Remove erroneous references to lexicon category image and summary ([fca33f](https://code.itinerare.net/itinerare/Mundialis/commit/fca33fa6ff55410a2e99dae0cfc000fb9d9d47ab))

##### Language

* Allow clearing lexicon category data ([b7d3a4](https://code.itinerare.net/itinerare/Mundialis/commit/b7d3a4c419e683ec3fca93fc3055d6f00cadf86e))
* Check if an entry exists in a lexicon category when attempting delete ([b4f712](https://code.itinerare.net/itinerare/Mundialis/commit/b4f712f50e2f4946c90c9af8f461de7498c58727))
* Do not silently discard attributes when creating/editing lexicon categories ([11abbc](https://code.itinerare.net/itinerare/Mundialis/commit/11abbc83d76c3734c66d03d54bc1b08370110895))
* Make delete lexicon category verbiage clearer ([2f39a8](https://code.itinerare.net/itinerare/Mundialis/commit/2f39a8d7f63649fb425bb8c29eac8811a2e87592))

##### Lexicon

* Do not silently discard attributes when creating/editing entries ([b40fab](https://code.itinerare.net/itinerare/Mundialis/commit/b40fab041b2530a18ccb7a57d71f82e478165313))

##### Page Images

* Always eager load basic page details ([b7800f](https://code.itinerare.net/itinerare/Mundialis/commit/b7800f46195bca904c81ea5248412fef335fbace))
* Better visibility check on create/edit ([ac7655](https://code.itinerare.net/itinerare/Mundialis/commit/ac765589f43cc912b1e949221dab11c2b629391e))
* Display format/size limit info in create/edit view ([285120](https://code.itinerare.net/itinerare/Mundialis/commit/285120a2411e33c18557131c07fc7c8df3432e97))
* Do not silently discard attributes when creating/editing ([e4e796](https://code.itinerare.net/itinerare/Mundialis/commit/e4e7961f08962cbbce13cce5ea3deda82574de78))
* Eager load versions, version users for deleted image view ([0932fc](https://code.itinerare.net/itinerare/Mundialis/commit/0932fcf1f2b0a9635b25386662b22a39e3c859af))
* Eager load versions, version users for full image view ([212eed](https://code.itinerare.net/itinerare/Mundialis/commit/212eed266c05959178b36b71bbcb5c30f2c4d443))
* Improve image info modal check for viewing in a page context ([b600aa](https://code.itinerare.net/itinerare/Mundialis/commit/b600aa25a0a896d353d6f3bdbede66dae7507b33))
* Improve use cropper check in processing ([fa5a09](https://code.itinerare.net/itinerare/Mundialis/commit/fa5a09cb986dde50e09284641d421f2479abebbd))
* Use extension from file when saving processed image ([3445ee](https://code.itinerare.net/itinerare/Mundialis/commit/3445ee3b1533e75b53f3338ae97682b8e856e5ff))

##### Pages

* 404 if attempting to edit a non-existent page ([bfc71f](https://code.itinerare.net/itinerare/Mundialis/commit/bfc71fc7039de61fff88443596f0ac8bec7cbdb4))
* Add page visibility check to image visibility, recent images special page ([90f556](https://code.itinerare.net/itinerare/Mundialis/commit/90f556dd6eb24c3139dac4891c3e86468647d9ff))
* Always eager load common page and image relations ([738aac](https://code.itinerare.net/itinerare/Mundialis/commit/738aacb66cf1d585280c7100a899a45ef4032838))
* Always eager load page protections ([94b88c](https://code.itinerare.net/itinerare/Mundialis/commit/94b88c8a963b7b3485d98d70e1500f6123f442ac))
* Better checks for category existence when creating a page ([09245c](https://code.itinerare.net/itinerare/Mundialis/commit/09245c042fd3530514214aae4080d6c2d636b5a6))
* Cascade page protections to linked images ([6c239a](https://code.itinerare.net/itinerare/Mundialis/commit/6c239ab86e4517efa4c21cf8fae6be2ec6c9482d))
* Check for version existence when resetting page ([4ab135](https://code.itinerare.net/itinerare/Mundialis/commit/4ab13520e1601811f8a259a647967d2cc6d3711d))
* Check that page/image is deleted when getting/restoring a deleted page/image ([6c8e43](https://code.itinerare.net/itinerare/Mundialis/commit/6c8e439f096d91e7911f91ee5a5ba4831e549481))
* Clean up page existence checks in ImageController ([095949](https://code.itinerare.net/itinerare/Mundialis/commit/095949e821e742b8e4197d83ac143d430b8172b3))
* Do not silently discard attributes when creating/editing ([667335](https://code.itinerare.net/itinerare/Mundialis/commit/667335af8237fa6c64be36d3578f7cc90865c418))
* Do not silently discard attributes when resetting page ([598108](https://code.itinerare.net/itinerare/Mundialis/commit/598108d9bf5aa5de2c693fb7bda5dcb4e9ace2f0))
* Eager load category, parent for additional associated views ([3d361e](https://code.itinerare.net/itinerare/Mundialis/commit/3d361e87f2a6361bb0dacbfb9016e86401776269))
* Eager load category, parent for associated views ([af2263](https://code.itinerare.net/itinerare/Mundialis/commit/af2263bfa6097eaf4c399b722fc23e93a31f24ad))
* Ensure slugs beginning with numbers don't 404 ([b709b4](https://code.itinerare.net/itinerare/Mundialis/commit/b709b4fcc72f9930029477b4c79e02e2498d6467))
* Fix linked to special page text ([bd7a17](https://code.itinerare.net/itinerare/Mundialis/commit/bd7a171f76bae51ed02b26794026689ec2729d61))
* Fix most linked special page visibility check, display ([87f697](https://code.itinerare.net/itinerare/Mundialis/commit/87f69789c4a08a1039f464fd591c385a9d30287b))
* Fix route error getting image info from special pages ([b2db94](https://code.itinerare.net/itinerare/Mundialis/commit/b2db94cc542ea2ed04854ccb9f56f7dc8219e703))
* Fix route error getting tag page ([8c754d](https://code.itinerare.net/itinerare/Mundialis/commit/8c754d5255d40d27919fa672b35436cd5633d8c5))
* Handle error better on trying to get delete page for an invalid page ([a168b1](https://code.itinerare.net/itinerare/Mundialis/commit/a168b1333b96c261972f59c5c2e1dbc5c5d78ff9))
* Improve check for relationship existence when deleting it ([475dfb](https://code.itinerare.net/itinerare/Mundialis/commit/475dfb578fcfc66a30206dfda6bd68a9e9bd8469))
* Improve page existence checks in controller ([59040d](https://code.itinerare.net/itinerare/Mundialis/commit/59040d95dc813df5e0b5171ff383041ae7944ef1))
* Improve restore image page count checks ([2729ae](https://code.itinerare.net/itinerare/Mundialis/commit/2729ae196dad6ebe5ff4962a78a400331ad2a6f0))
* Improve version lookup in postResetPage function ([caa50f](https://code.itinerare.net/itinerare/Mundialis/commit/caa50f44d853cb887f0639e4a8a66206f11e9158))
* Move page exsistence checks from controller to service ([d3c417](https://code.itinerare.net/itinerare/Mundialis/commit/d3c417b70e27cffaa5b3b299ef3d48ccf9bf567d))
* Only eager load parent, category, tags where relevant ([52265b](https://code.itinerare.net/itinerare/Mundialis/commit/52265b385d141b4c640863ff22b3cde276742df9))
* Only show relationships for people pages ([355b65](https://code.itinerare.net/itinerare/Mundialis/commit/355b6590bf74f6beb39bcccf7ec84a11a7536103))
* Validate create wanted page category ID selection ([54b016](https://code.itinerare.net/itinerare/Mundialis/commit/54b0162018fb21edfbdd89cdfd970b9b42b3e1d0))
* Validate utility tag input against config file ([d772b4](https://code.itinerare.net/itinerare/Mundialis/commit/d772b4f6f0bfa0a8780ed481276f4dadbf2027c7))

##### Routes

* Fix issues reading routes in other files, get tag routes ([3e92f3](https://code.itinerare.net/itinerare/Mundialis/commit/3e92f3a4c35274476372f0c86f812924bbc09b38))
* Resolve issues reading admin routes ([a64950](https://code.itinerare.net/itinerare/Mundialis/commit/a6495031304f88d46eb03c7203c027aa665be00b))
* Rewrite in new syntax ([e548fe](https://code.itinerare.net/itinerare/Mundialis/commit/e548fe1a38450511486abc66a94ff29a63140a28))

##### Tests

* Add cleanup of users to test case ([11bc74](https://code.itinerare.net/itinerare/Mundialis/commit/11bc74bcde3190bd67c9c16228adbdb711257fcf))
* Add fallbacks in subject template viewing/processing ([488d9d](https://code.itinerare.net/itinerare/Mundialis/commit/488d9d2dfce1326c2eb144699e38273cebc5469f))
* Additional user ban errors not added to session errors ([dd12a3](https://code.itinerare.net/itinerare/Mundialis/commit/dd12a3f1ea93b7fc9e3dd21d565808878164a686))
* Add pre-test cleanup to test files that need it ([9a4c6e](https://code.itinerare.net/itinerare/Mundialis/commit/9a4c6eb1525c1d4b2d767d8ee73ba6f30bd1b3df))
* Add textbox tests to get create/edit page field tests ([ae9ca0](https://code.itinerare.net/itinerare/Mundialis/commit/ae9ca0e4ec64432ae07241940e1ff91e2ecbb247))
* Clean up test images after page restore tests ([75af40](https://code.itinerare.net/itinerare/Mundialis/commit/75af40ec708ac51f856aeebcac350677220196cd))
* Delete lexicon entries/etymologies before performing tests ([6a3d50](https://code.itinerare.net/itinerare/Mundialis/commit/6a3d5013a7388f7ef8643aef7442632205544402))
* Delete notifications before performing send notification tests ([c37d9a](https://code.itinerare.net/itinerare/Mundialis/commit/c37d9a4c05138300a14543c960d56704cdb73a99))
* Explicitly specify default values in user factory ([911b53](https://code.itinerare.net/itinerare/Mundialis/commit/911b53993155d33ed9f51b663d1f3bcb93d7816a))
* Fix invalid testData json in page version factory ([204dac](https://code.itinerare.net/itinerare/Mundialis/commit/204dac295a7f712923aeb7f9ca553821788fc1ed))
* Generate safer usernames in user factory ([2a032f](https://code.itinerare.net/itinerare/Mundialis/commit/2a032f4297c8b0df6813b8c8c6a673f353e697a5))
* Make page titles used in page edit tests more unique ([a42bce](https://code.itinerare.net/itinerare/Mundialis/commit/a42bce1a0f87aef81d84b3764c9253789f4504dd))
* Make user list test more flexible ([63e6fb](https://code.itinerare.net/itinerare/Mundialis/commit/63e6fb609831d34c7423e4f41b4ed29994ecc952))
* Mark to-update tests as incomplete ([7ac212](https://code.itinerare.net/itinerare/Mundialis/commit/7ac2127ac0d90bf22268a4a0364f33da13a72c90))
* More accurate mutliple choice field handling in page edit field tests ([c50660](https://code.itinerare.net/itinerare/Mundialis/commit/c5066091a226505102f5264025d8ab1f2e3700cd))
* Remove boolean sequences function from test case ([1ac959](https://code.itinerare.net/itinerare/Mundialis/commit/1ac9598d5f67b8811a9c163b24a3b2929098eaf9))
* Replace randomized invalid IDs with 9999 ([9c8237](https://code.itinerare.net/itinerare/Mundialis/commit/9c82370e2c6f8e5f3931b4c30920afeaddd3372e))
* Resolve errors due to a larger volume of tests ([8daeed](https://code.itinerare.net/itinerare/Mundialis/commit/8daeed60d0088a6c2cd1608f486bd9de4a672f95))
* Revert special page user list test ([df4037](https://code.itinerare.net/itinerare/Mundialis/commit/df4037bd516e654b4196c10eac51c46f2589364f))
* Site setting errors not added to session errors ([291096](https://code.itinerare.net/itinerare/Mundialis/commit/29109692b160557a6f9e9baab460c782556f1d4e))
* Specify filename from version in create image test checks ([5c7e11](https://code.itinerare.net/itinerare/Mundialis/commit/5c7e11b3bec605a5b35dcc6554de62b944bfcd3b))
* Unlink file after user avatar test ([b108e6](https://code.itinerare.net/itinerare/Mundialis/commit/b108e632e4f7d065079ff703882f5b21ea9ef547))
* Update password reset tests ([f3961d](https://code.itinerare.net/itinerare/Mundialis/commit/f3961d79ffe13f45a08555870cca5add5e3b52d8))
* Use rank sort in admin user edit tests ([0e0ee8](https://code.itinerare.net/itinerare/Mundialis/commit/0e0ee85d6158776b4de2eca2eeaad211e86564df))
* User editing errors not added to session errors ([10ef9a](https://code.itinerare.net/itinerare/Mundialis/commit/10ef9a7dcd4bda34bb13bee2b16f98469de10d23))

##### Time

* Check if a page exists in a chronology when attempting delete ([794524](https://code.itinerare.net/itinerare/Mundialis/commit/7945244ff1fa2c1ba9371f0f55e31b8c3e2094e9))
* Fix chronology breadcrumbs with a parent ([add2af](https://code.itinerare.net/itinerare/Mundialis/commit/add2af58060033095faf34b3d300419a22dfb13a))
* Fix filtering chronology pages by category ([28ff4e](https://code.itinerare.net/itinerare/Mundialis/commit/28ff4e0718c19f307490965057a0cc773e83cd81))
* Remove erroneous references to chronology image and summary ([c8a23b](https://code.itinerare.net/itinerare/Mundialis/commit/c8a23b83ae5e7c7418601a76fcca725630991edb))

##### Users

* Always eager load rank ([a333c8](https://code.itinerare.net/itinerare/Mundialis/commit/a333c87f32c1c746bbd2fb3fdd9ba71d37cc6601))
* Check if user exists when posting ban before checking ban status ([c21e63](https://code.itinerare.net/itinerare/Mundialis/commit/c21e630afa7b1acba529888f46cc58f5bff020b7))
* Error on unban/confirmation re non-banned users ([4c5f7a](https://code.itinerare.net/itinerare/Mundialis/commit/4c5f7aba7da6b1f6b2bbcfdc1ea82e95c91f9a9f))
* Improve page watch visibility/existence check ([ba8df4](https://code.itinerare.net/itinerare/Mundialis/commit/ba8df45a032b85bf902ea7af8fd824658b41bf22))


---

## [2.2.6](https://code.itinerare.net/itinerare/Mundialis/compare/v2.2.5...v2.2.6) (2024-02-25)


---

## [2.2.5](https://code.itinerare.net/itinerare/Mundialis/compare/v2.2.4...v2.2.5) (2024-01-14)

### Bug Fixes


##### Users

* Improve password reset flow ([c04a07](https://code.itinerare.net/itinerare/Mundialis/commit/c04a0794ac44334fabb833e52a2ff4be978992ae))


---

## [2.2.4](https://code.itinerare.net/itinerare/Mundialis/compare/v2.2.3...v2.2.4) (2023-08-20)

### Bug Fixes

* Admin subject category table format/header ([93b561](https://code.itinerare.net/itinerare/Mundialis/commit/93b561f1fa15df3c01ba689270788a8a9e363644))

##### Time

* Fix viewing chronology with children ([96d207](https://code.itinerare.net/itinerare/Mundialis/commit/96d207bfeacf5a19a90cc153e3af5d0437d8da9f))

##### Users

* Improve avatar handling ([4e63a0](https://code.itinerare.net/itinerare/Mundialis/commit/4e63a055a15631573f52e629d1a9b11e3d5b0c6a))


---

## [2.2.3](https://code.itinerare.net/itinerare/Mundialis/compare/v2.2.2...v2.2.3) (2023-08-13)

### Bug Fixes


##### Lang

* Fix uncategorized entry displayName link ([e8646e](https://code.itinerare.net/itinerare/Mundialis/commit/e8646e5292a287d5d19bb17ca22e83b26f317758))


---

## [2.2.2](https://code.itinerare.net/itinerare/Mundialis/compare/v2.2.1...v2.2.2) (2023-08-06)

### Bug Fixes


##### Lang

* Fix descendent link for a non-categorized entry ([2ef9f0](https://code.itinerare.net/itinerare/Mundialis/commit/2ef9f0858a84d21ccd054d860594993d7ab8a982))

##### Pages

* Add show/hide text swap JS to version view ([9c5c5a](https://code.itinerare.net/itinerare/Mundialis/commit/9c5c5abf5e03a173b6ee09e6770a8f26cbbed1a6))

##### Users

* More refined deleted user check on invitation page ([3420dd](https://code.itinerare.net/itinerare/Mundialis/commit/3420ddba96a47e5b15d1579375f132f96b21b54c))


---

## [2.2.1](https://code.itinerare.net/itinerare/Mundialis/compare/v2.2.0...v2.2.1) (2023-07-30)

### Bug Fixes


##### Users

* Add extra check for recipient on invitation code page ([aad6f6](https://code.itinerare.net/itinerare/Mundialis/commit/aad6f6972f69b1ad754a65ef7498c77e28454b84))


---

## [2.2.0](https://code.itinerare.net/itinerare/Mundialis/compare/v2.1.3...v2.2.0) (2023-07-23)

### Features

* Set up mix, update bootstrap ([480b87](https://code.itinerare.net/itinerare/Mundialis/commit/480b875da2cfa0fcab66a917a43a1de1da1c9662))


---

## [2.1.3](https://code.itinerare.net/itinerare/Mundialis/compare/v2.1.2...v2.1.3) (2023-07-16)

### Bug Fixes

* Fix adding/editing help tooltips for infobox fields ([d1d527](https://code.itinerare.net/itinerare/Mundialis/commit/d1d527f2994832db4043c92826e50e2c6e814826))
* General PHP clean-up to help static analysis ([c8df23](https://code.itinerare.net/itinerare/Mundialis/commit/c8df23030f5bda5c277b8e4337a2478266a5b82b))
* Prevent basic page field key overlap ([a14db1](https://code.itinerare.net/itinerare/Mundialis/commit/a14db1a834072c2b1d6c2d925892b53c33b992ef))

##### Pages

* Route to pages with slugs beginning with numbers; closes #374 ([98f325](https://code.itinerare.net/itinerare/Mundialis/commit/98f325c2bc53364225db2be9b3a70b3476e26ac8))

##### Time

* Resolve some timeline formatting issues ([396d83](https://code.itinerare.net/itinerare/Mundialis/commit/396d836e0cf614442e53bbaed9a72451fe527316))


---

## [2.1.2](https://code.itinerare.net/itinerare/Mundialis/compare/v2.1.1...v2.1.2) (2023-05-07)

### Bug Fixes


##### Pages

* Allow all utility tags to be removed from a page ([3bb16c](https://code.itinerare.net/itinerare/Mundialis/commit/3bb16cfbd790aaea7538e327729b6a5685bf8aed))
* Allow special and accented characters to be correctly parsed by wiki link syntax ([35d5d9](https://code.itinerare.net/itinerare/Mundialis/commit/35d5d915d3156539a63f4b9176fef173ea31d52f))


---

## [2.1.1](https://code.itinerare.net/itinerare/Mundialis/compare/v2.1.0...v2.1.1) (2023-04-23)

### Bug Fixes


##### Pages

* Always show image creator fields if no creators are set ([ee3ae4](https://code.itinerare.net/itinerare/Mundialis/commit/ee3ae4d4d7d507bee71cc4fa79c2776d6b39324f))
* Properly require one of image creator ID/URL ([006d94](https://code.itinerare.net/itinerare/Mundialis/commit/006d946439207d0979ac75d46e02acbef56cd3a8))

##### Tests

* Fix sent data in edit image creator with user test ([a5ee9b](https://code.itinerare.net/itinerare/Mundialis/commit/a5ee9b85ca7551bf6a21cbd90aabeecc39aa510c))


---

## [2.1.0](https://code.itinerare.net/itinerare/Mundialis/compare/v2.0.0...v2.1.0) (2022-07-24)

### Features

* Add code coverage test to composer scripts ([643c8b](https://code.itinerare.net/itinerare/Mundialis/commit/643c8b0fb40bbbecff66b64e1358067388c92cc9))


---

## [2.0.0](https://code.itinerare.net/itinerare/Mundialis/compare/v1.3.5...v2.0.0) (2022-05-15)
### ⚠ BREAKING CHANGES

* Update to Laravel 9 ([60f70d](https://code.itinerare.net/itinerare/Mundialis/commit/60f70dc0bc8dfa1db708e0539e687749766e33a2))
* Update to PHP 8 ([e1947c](https://code.itinerare.net/itinerare/Mundialis/commit/e1947c4629a923d718da4b59f72eae491e5d78cd))


---

## [1.3.3](https://code.itinerare.net/itinerare/Mundialis/compare/v1.3.2...v1.3.3) (2022-02-06)
### Bug Fixes

* Headers already sent error; fixes #70 ([b37b63](https://code.itinerare.net/itinerare/Mundialis/commit/b37b630b2a387f43804909936c6a1db5e09c415a))


---

## [1.3.2](https://code.itinerare.net/itinerare/Mundialis/compare/v1.3.1...v1.3.2) (2022-01-31)
### Bug Fixes


##### License

* Update info around patron license ([3fac0b](https://code.itinerare.net/itinerare/Mundialis/commit/3fac0b90d5759c65894518ec7903ef74a7641cec))


---

## [1.3.1](https://code.itinerare.net/itinerare/Mundialis/compare/v1.3.0...v1.3.1) (2022-01-30)
### Bug Fixes


##### Time

* Clarify division page verbiage ([c1c2f6](https://code.itinerare.net/itinerare/Mundialis/commit/c1c2f6a5e4a597037ec746e94902374c0b3ea450))
* Compatibility measures for old dates ([0572c6](https://code.itinerare.net/itinerare/Mundialis/commit/0572c65b2d74689c9a685b05ce30c268f1cec311))
* Date fields not keyed by ID ([14a72f](https://code.itinerare.net/itinerare/Mundialis/commit/14a72fba1e62749113bb71faf1c180ae7a25b046))
* Error formatting timeline ([a398b4](https://code.itinerare.net/itinerare/Mundialis/commit/a398b47fcfbcdbc460e6a33716fdf9bc23eeea2b))
* Error viewing date with deleted divisions; fixes #55 ([412845](https://code.itinerare.net/itinerare/Mundialis/commit/412845cc25a6bc76809b55aa1924bd0a0e5a1e17))


---

## [1.3.0](https://code.itinerare.net/itinerare/Mundialis/compare/v1.2.0...v1.3.0) (2022-01-16)
### Features


##### Lang

* Switch lexicon settings to input groups; closes #26 ([0e3720](https://code.itinerare.net/itinerare/Mundialis/commit/0e37208604b64cbcd2a85c5d1f4e09654cc86654))

##### Tests

* Add subject-specific page view tests; closes #45 ([47797d](https://code.itinerare.net/itinerare/Mundialis/commit/47797dde1e90e03c8886da611caa7e21a15dd384))

##### Time

* Switch divisions to input groups ([dc16b1](https://code.itinerare.net/itinerare/Mundialis/commit/dc16b16bf6b7f29f5f03b78b6c09d64166fa5014))

### Bug Fixes

* Editing subject category unsets has_image ([5fafe2](https://code.itinerare.net/itinerare/Mundialis/commit/5fafe2183795787928e3aadf89e79dcdef6d6ac6))

##### Lang

* Error removing all lexicon settings ([12c7fb](https://code.itinerare.net/itinerare/Mundialis/commit/12c7fb356797d11a531ed05e2c25529fc7444711))

##### Pages

* Change section show/hide w/ state; fixes #29 ([6fcbaf](https://code.itinerare.net/itinerare/Mundialis/commit/6fcbaf93bca72ca12911ab35a0d83c7e371b5fa2))
* Error falling back to subject template ([67d63f](https://code.itinerare.net/itinerare/Mundialis/commit/67d63f3d9dece1bdc565d07d6b7d4703808171f3))
* Template fetch error w nested categories ([bdf0c1](https://code.itinerare.net/itinerare/Mundialis/commit/bdf0c125c7dd31ca28da5780d82a473d3bee9812))

##### People

* Error displaying birth/death w/o date ([cde3cb](https://code.itinerare.net/itinerare/Mundialis/commit/cde3cb6f3b847d2bc01c8d31d7054ccd31e159a7))

##### Tests

* Actually create page vers in view test ([98a338](https://code.itinerare.net/itinerare/Mundialis/commit/98a33825d8fdb22ff53f6d803a71f0717d83d062))

##### Time

* Better fix for removing all divisions ([f00479](https://code.itinerare.net/itinerare/Mundialis/commit/f004791086a90e31a63d2a668b9fdd9b0a8f09df))
* Error removing all time divisions ([75a2ac](https://code.itinerare.net/itinerare/Mundialis/commit/75a2aca440434af79488543e254a4e8c7087d2e2))


---

## [1.2.0](https://code.itinerare.net/itinerare/Mundialis/compare/v1.1.1...v1.2.0) (2022-01-09)
### Features


##### Tests

* Add template field tests; closes #25 ([cd0272](https://code.itinerare.net/itinerare/Mundialis/commit/cd027294e14220e1620a5f3800baf7a1f22c86bf))
* Extend template field tests ([312183](https://code.itinerare.net/itinerare/Mundialis/commit/3121830ed3ee2588c32f378888e3fdadb6e88aaf))
* Infobox page edit field tests ([f75ad9](https://code.itinerare.net/itinerare/Mundialis/commit/f75ad9c8524bd804e0abd3c6925ac75497f57c2c))
* Page body edit field tests ([241ee1](https://code.itinerare.net/itinerare/Mundialis/commit/241ee1f1e8c621d58695e4d03699cfd24214e85b))
* Page view field tests ([d3efb7](https://code.itinerare.net/itinerare/Mundialis/commit/d3efb75049e57cf60dc9a5716f266bead16e520d))

### Bug Fixes

* Error editing infobox field default value ([e4b61e](https://code.itinerare.net/itinerare/Mundialis/commit/e4b61ea033cd6a7ebe9e7acca0d27fc4113687bb))
* Make default value/choices exclusive for fields ([b769b5](https://code.itinerare.net/itinerare/Mundialis/commit/b769b57c7c7fa7f9e11db0c9848a2c6361f4f3c0))
* Template builder entry verbiage ([2186bb](https://code.itinerare.net/itinerare/Mundialis/commit/2186bbc3b60097d7508430931fc5e7b79fc5e136))

##### Pages

* Choice/mult display error in infobox ([563fa4](https://code.itinerare.net/itinerare/Mundialis/commit/563fa4207f236fada18e87ef86c61f334304af73))
* Fix using default value ([b9e22c](https://code.itinerare.net/itinerare/Mundialis/commit/b9e22ce26ec11acb0d23fa8b34595b529eca866d))
* Radio buttons don't re-fill properly ([77660d](https://code.itinerare.net/itinerare/Mundialis/commit/77660d63c6c32ce037efcec6bf513da5107d9b10))
* View error with certain field types ([dd5831](https://code.itinerare.net/itinerare/Mundialis/commit/dd5831e40ddecb84eac9cba8be7e62aa0d055ee2))

##### Tests

* Test class name issue ([4f4cfe](https://code.itinerare.net/itinerare/Mundialis/commit/4f4cfe733a75ca2f9bd0e84112a1326ca394e98f))


---

## [1.1.1](https://code.itinerare.net/itinerare/Mundialis/compare/1.1.0...v1.1.1) (2021-12-05)
### Bug Fixes


##### Backups

* Config cleanup; fixes #33 ([82679d](https://code.itinerare.net/itinerare/Mundialis/commit/82679d60b991fba4607af5ca16f8ffd3f08490e7))

---

## [1.1.0](https://code.itinerare.net/itinerare/Mundialis/compare/1.0.0...1.1.0) (2021-11-28)
### Features


##### Backups

* Set up backups ([20cffd](https://code.itinerare.net/itinerare/Mundialis/commit/20cffd3d9acad1c3aba99c02a6ab92efe3d021f8))

### Bug Fixes

* Cannot create choose (x) infobox fields; fixes #24 ([866dc1](https://code.itinerare.net/itinerare/Mundialis/commit/866dc102bcccecec7dffbc3f60b7def2cbda395d))
* Existing infobox field type JS issue ([2a49d8](https://code.itinerare.net/itinerare/Mundialis/commit/2a49d870ada7d2213544b0d8faceaf5f1aa69e1c))

##### Pages

* Error viewing pages after adding field ([7f62c4](https://code.itinerare.net/itinerare/Mundialis/commit/7f62c488073b31e0491f020922c4350822d8aa03))
* Multiple choice errors; fixes #28 ([04d143](https://code.itinerare.net/itinerare/Mundialis/commit/04d14371e84e5cce2c9fe85de4fdfd6f3b2bb09c))

---

## [1.0.0](https://code.itinerare.net/itinerare/Mundialis/compare/1.0.0-rc1...1.0.0) (2021-11-23)
### Features

* Add category summaries and images ([7398cb](https://code.itinerare.net/itinerare/Mundialis/commit/7398cbc7fd5da1c486ac1ae40f4481e5bd4d8afb))
* Add further .env files to ignore ([532f94](https://code.itinerare.net/itinerare/Mundialis/commit/532f9445788430018e139fa6673e007e2e0d6010))
* Add general update command ([52e4e2](https://code.itinerare.net/itinerare/Mundialis/commit/52e4e2e0e76c7a17cda9ae0bd0b7d3c3e2bad68e))
* Add infobox/section sorting to templates ([c9e188](https://code.itinerare.net/itinerare/Mundialis/commit/c9e188f3b66e2fda95f891b1aa2b3445baabf4c3))
* Add page watching/watched pages ([40df1b](https://code.itinerare.net/itinerare/Mundialis/commit/40df1b541c1060425bb6a65f3eae1e603b760781))
* Add setup command, for convenience ([888075](https://code.itinerare.net/itinerare/Mundialis/commit/888075a6876e786a90a8e15a0ce320ce52e7188c))
* Add site settings ([5145e2](https://code.itinerare.net/itinerare/Mundialis/commit/5145e26001167245f90b82e679287a91bf671da9))
* Add subject category creation and editing ([efa86a](https://code.itinerare.net/itinerare/Mundialis/commit/efa86a475ae5fdd18d444ab9f6caf42764447b22))
* Add subject links to navbar ([fe7958](https://code.itinerare.net/itinerare/Mundialis/commit/fe79580799e933f2383c388eae1ee9a761826a43))
* Add subject selection to template sections ([55dfad](https://code.itinerare.net/itinerare/Mundialis/commit/55dfad61cf3a6df20fb79424eb424b9855cc7b7c))
* Add widget selection to template builder ([bc0a17](https://code.itinerare.net/itinerare/Mundialis/commit/bc0a17f876e792aaefa7a18a58955fabdc956ace))
* Allow sorting new infobox/template sections ([a621fd](https://code.itinerare.net/itinerare/Mundialis/commit/a621fda905fd463dc84ebaf29e24038aec7c8880))
* Basic admin panel setup ([b83d4a](https://code.itinerare.net/itinerare/Mundialis/commit/b83d4a97a3a998596fc6d8e73794e5afff66d3a7))
* Basic auth setup and views ([725ed8](https://code.itinerare.net/itinerare/Mundialis/commit/725ed8b6f512717ed428237da00d9a6bad30c94f))
* Basic site page setup ([b5f8bf](https://code.itinerare.net/itinerare/Mundialis/commit/b5f8bf5cbf6b2263a1e3fcab51d4055b3fec09cb))
* Clean up widgets ([dfcd60](https://code.itinerare.net/itinerare/Mundialis/commit/dfcd6086a72295facecb73501b82b1f0b163da4d))
* Clearer instructions around template editing ([3fdd4e](https://code.itinerare.net/itinerare/Mundialis/commit/3fdd4e19d23e802fe14d8c45dba331d8d845fce1))
* Helper functions ([4073cf](https://code.itinerare.net/itinerare/Mundialis/commit/4073cf0ccab141d7353dee5938bd39b033db934e))
* Implement basic template editor for subjects ([3f7628](https://code.itinerare.net/itinerare/Mundialis/commit/3f76287486ab51ac45e8ac900e8072ed4eb4b04f))
* Implement cascading changes for templates ([aaf235](https://code.itinerare.net/itinerare/Mundialis/commit/aaf235471e01a1aac22f7e580a3450136462843d))
* Implement cascading for category templates ([1fd9a7](https://code.itinerare.net/itinerare/Mundialis/commit/1fd9a7479e45b02b44158cff70a377fbde108f8b))
* Implement category sorting ([6740c4](https://code.itinerare.net/itinerare/Mundialis/commit/6740c4a64c9f6279c28d6a075b6469df237fad2e))
* Implement more basic perms checks and route setup ([d64c25](https://code.itinerare.net/itinerare/Mundialis/commit/d64c25474f64efc566649ceb57db5ed4ba0106b6))
* Implement rank editing ([90d9f9](https://code.itinerare.net/itinerare/Mundialis/commit/90d9f9357156e9b9ce190ab3751afdda650509be))
* Navbar, dashboard updates ([356e5e](https://code.itinerare.net/itinerare/Mundialis/commit/356e5e20c81b346ae8ba44fa88689566a5f4eb2c))
* Replace placeholder images with solid colour ([0a15d3](https://code.itinerare.net/itinerare/Mundialis/commit/0a15d30d15e60b992c7d8e18e30ce392af6018a9))
* Start of admin template panels/routes ([65a32f](https://code.itinerare.net/itinerare/Mundialis/commit/65a32f6202e4ea2416bf3f74b057e73f96fd8fe5))
* Tos/privacy policy pages/fix them ([7d7480](https://code.itinerare.net/itinerare/Mundialis/commit/7d7480430bbebabea901b86edd647db06ace41db))
* Update subjects config, rename subject view ([6dd20b](https://code.itinerare.net/itinerare/Mundialis/commit/6dd20b8ced15cdbfba7acb18c100ad58a55556cb))

##### Auth

* Add admin user edits, email functions ([7aae88](https://code.itinerare.net/itinerare/Mundialis/commit/7aae885325cf7d7405a92903006de45afc8e764b))
* Add page delete notification ([7d3241](https://code.itinerare.net/itinerare/Mundialis/commit/7d32411ae567bb56538fb8b12169475fd27f93da))
* Adjust user/acc page title formatting ([dae788](https://code.itinerare.net/itinerare/Mundialis/commit/dae788805c687a7d0c118cf8fce546e5e6d8f9e6))
* Implement user profiles ([3f2eb0](https://code.itinerare.net/itinerare/Mundialis/commit/3f2eb0e357b2725949838b6c1f8b3c8d861c4b2f))
* Implement user settings ([092fb6](https://code.itinerare.net/itinerare/Mundialis/commit/092fb65ca06efe2f888dde2ccf4e7ac414dc34f6))
* Invitation code system ([b052d3](https://code.itinerare.net/itinerare/Mundialis/commit/b052d30edd10267d28acc2d222c4f38a693e1d0f))
* Notification system ([3eeaab](https://code.itinerare.net/itinerare/Mundialis/commit/3eeaabeb8b0adba792dd6f7e658b6e4072bd9447))

##### Lang

* Add category settings population ([e208b5](https://code.itinerare.net/itinerare/Mundialis/commit/e208b51d67e661d9520f5062f027aab427cb766e))
* Add etymology and display to entries ([5fa464](https://code.itinerare.net/itinerare/Mundialis/commit/5fa46477615d81eeb51b281429d22cd0d68e22a2))
* Add lexicon settings and categories ([f4c806](https://code.itinerare.net/itinerare/Mundialis/commit/f4c806456678ac739a04a89e2f236372b2f7a46b))
* Add page linking in definitions ([4123ee](https://code.itinerare.net/itinerare/Mundialis/commit/4123eef5a562f48cf8cdc8a28e7f1023191b6c98))
* Add recursive entry descendant listing ([d3ca77](https://code.itinerare.net/itinerare/Mundialis/commit/d3ca775f397525675d53e4b4112b3e9e2f4e8a74))
* Add sort by meaning to entry index ([ac6b05](https://code.itinerare.net/itinerare/Mundialis/commit/ac6b053b676f0eafd3b5cf7043fe858800a4f310))
* Capitalize ucfirst()'d entry autoconj ([6f99e2](https://code.itinerare.net/itinerare/Mundialis/commit/6f99e2089148c12048f83501d75869e549055906))
* Easier editing of auto-conj rules ([1bedd5](https://code.itinerare.net/itinerare/Mundialis/commit/1bedd5dc1d531d6187996160ad42b4f19073873c))
* Entry conjugation/declension, display ([0c25cb](https://code.itinerare.net/itinerare/Mundialis/commit/0c25cbb3548007406b8fc4cfa5380832c23f0003))
* Finish adding category delete and sort ([f191f9](https://code.itinerare.net/itinerare/Mundialis/commit/f191f920444cb3765e6b8571079bd491866dff73))
* Lexicon entry display and creation ([fb783c](https://code.itinerare.net/itinerare/Mundialis/commit/fb783cdb71e6a0f6ee9b55bd3d3d291a97d64e57))
* Make conj/decl collapsible ([be4243](https://code.itinerare.net/itinerare/Mundialis/commit/be4243c4c2e02ffafa72f45142717c1889cdf8d7))
* Sort entry descendants ([7c45eb](https://code.itinerare.net/itinerare/Mundialis/commit/7c45eb3fc0dea53f287e6e451de07b6b6765b2a7))

##### Pages

* Add ability to filter by tag(s) ([9dc371](https://code.itinerare.net/itinerare/Mundialis/commit/9dc371cb2592ae5b36821a33720bf34bd4967c22))
* Add advanced tags + navboxes ([ddb8c9](https://code.itinerare.net/itinerare/Mundialis/commit/ddb8c905f20333dcdb2847beed18ca10d39016e9))
* Add auth-dependent visibility ([7fe483](https://code.itinerare.net/itinerare/Mundialis/commit/7fe4837abaaeaf46ca6b13a4176b8b7b9e1f4a5f))
* Add basic page creation and editing ([6befee](https://code.itinerare.net/itinerare/Mundialis/commit/6befeea7cde6a6d4a39a16ab1241dfc28f57792e))
* Add basic page display ([94850e](https://code.itinerare.net/itinerare/Mundialis/commit/94850e288bd450eb07395efc9997ed7749ae0841))
* Add basic search/sort to page indexes ([6443d0](https://code.itinerare.net/itinerare/Mundialis/commit/6443d081516fc34eef5674dd33ac4e370fe0930e))
* Add disambiguation features ([732140](https://code.itinerare.net/itinerare/Mundialis/commit/7321408cdb280d281df2fad79b9ee1b366b7952e))
* Add list of recent page/image changes ([27a853](https://code.itinerare.net/itinerare/Mundialis/commit/27a853328017ca43005a788d75a4edbc5e5ab8f3))
* Add optgroups to image page selection ([4686b7](https://code.itinerare.net/itinerare/Mundialis/commit/4686b76684af34064cf55c3cc45bb4f4bb2f7439))
* Add page image system ([f68807](https://code.itinerare.net/itinerare/Mundialis/commit/f688076b7607e08bb2b4b2ae87763eff6f810d9b))
* Add page protection ([e5de77](https://code.itinerare.net/itinerare/Mundialis/commit/e5de77c272be4ee28112303033804429273e29d6))
* Add protected pages special page ([9bfe95](https://code.itinerare.net/itinerare/Mundialis/commit/9bfe95d3c077c907a817e96f92bd871e6c8328e2))
* Add reason and minor edit to versions ([82cad6](https://code.itinerare.net/itinerare/Mundialis/commit/82cad6c0e0bbf1264ff3dca1b9e7e02a452451c1))
* Add revision history, image history ([c736b2](https://code.itinerare.net/itinerare/Mundialis/commit/c736b2e63ef67f0961b27de8548007d19d1821b7))
* Add unwatched special page ([cc98cd](https://code.itinerare.net/itinerare/Mundialis/commit/cc98cdb18db9979d9ce9ca86aeb971ce5416008c))
* Add user list special page ([9f0239](https://code.itinerare.net/itinerare/Mundialis/commit/9f023950c26d00340e4364a85ea36a17bf4165bf))
* Add visibility display to page header ([1daea5](https://code.itinerare.net/itinerare/Mundialis/commit/1daea5b0751f9c5f37240c7d088e9e70d2aa9379))
* Add wanted pages, what links here ([fe8ebc](https://code.itinerare.net/itinerare/Mundialis/commit/fe8ebc9d5617dad16ffd81cf8b7739ed9afaf7cb))
* Add wiki-style link parsing ([38bd23](https://code.itinerare.net/itinerare/Mundialis/commit/38bd2352d25506eca33aba4bebd9cdcdcd6a365c))
* All pages special page ([2fcc70](https://code.itinerare.net/itinerare/Mundialis/commit/2fcc7015eace2a86afc2efc99e88007095926ad9))
* All tags list, all images list ([abcbe6](https://code.itinerare.net/itinerare/Mundialis/commit/abcbe60ac318cc0cde593bfcdc8651e6d9c2b0f5))
* Auto table of contents ([28ee51](https://code.itinerare.net/itinerare/Mundialis/commit/28ee517ca867fb4e46d828a5f32df63d690e0b22))
* Better org of category/page indices ([950f9b](https://code.itinerare.net/itinerare/Mundialis/commit/950f9b109a1e6bce9bcfd6abd44ddc7ccf549703))
* Flow for creating wanted pages ([d4fdc1](https://code.itinerare.net/itinerare/Mundialis/commit/d4fdc1c0b0ca85d2ce9f93772a0a53e0585c9e20))
* Implement page tags ([457b1e](https://code.itinerare.net/itinerare/Mundialis/commit/457b1e54b1eab6b6c656a33241915838c70c2128))
* Implement specialized subject fields ([94a7d9](https://code.itinerare.net/itinerare/Mundialis/commit/94a7d92f91f324ed019829a744b25da6267e57bc))
* Implement updates on wanted page creation ([0a230a](https://code.itinerare.net/itinerare/Mundialis/commit/0a230a87ae696837a0f026828f46b60e6ce473ed))
* Implement utility tags ([d99eee](https://code.itinerare.net/itinerare/Mundialis/commit/d99eeeefc3b9fedce9a8f299c1313d0e46c40288))
* Make sections collapseable ([548ab1](https://code.itinerare.net/itinerare/Mundialis/commit/548ab1fcb407f05b1369ec026a2b8fa4f19cc321))
* More precise people age calculation ([5aede2](https://code.itinerare.net/itinerare/Mundialis/commit/5aede2b8e3716c1c42119ff7170d95406f64f6e8))
* Most/least revised pages list ([994c96](https://code.itinerare.net/itinerare/Mundialis/commit/994c96afec9c74e06b1059b4a7c812113f724ac0))
* Most linked-to pages special page ([d5fa0c](https://code.itinerare.net/itinerare/Mundialis/commit/d5fa0cb5e7071b75c933344c790bbb0fba042fd5))
* Most tagged/untagged special pages ([f34a93](https://code.itinerare.net/itinerare/Mundialis/commit/f34a93a764e97315b68d3139f38b7423e06a7d8e))
* Page links display summary on hover ([66b003](https://code.itinerare.net/itinerare/Mundialis/commit/66b003439734d33c2a09cf18d4bd13c460732e30))
* Page moving ([9ccfe7](https://code.itinerare.net/itinerare/Mundialis/commit/9ccfe745fd0c2ce945807e28be2c32a93861b652))
* Subject and category index views ([5fc5e0](https://code.itinerare.net/itinerare/Mundialis/commit/5fc5e0ee838578fcf63e9c2de921f6de8676459a))

##### People

* Add basic relationship tracking ([ee2c63](https://code.itinerare.net/itinerare/Mundialis/commit/ee2c6381baa7319ab3f170c3a09c91372ac9cae5))
* Adjust ancestry styling ([20f141](https://code.itinerare.net/itinerare/Mundialis/commit/20f1412490c1b50a35c061be8f8508cf8e79cb40))
* Basic family tree/listing page ([eb0ab9](https://code.itinerare.net/itinerare/Mundialis/commit/eb0ab97b2b4865c5cd2eb2ed46f5b3b2f0d455db))

##### Resources

* Add a bunch of basic resources ([bd2eeb](https://code.itinerare.net/itinerare/Mundialis/commit/bd2eeb2f8dfd4e11377aa9cb4f38d58a97e5fea2))
* Add cache clear to update command ([4a31a7](https://code.itinerare.net/itinerare/Mundialis/commit/4a31a7d62745d494b84ef048d0f5a4917a143e57))
* Add command to generate changelog ([b4e2b8](https://code.itinerare.net/itinerare/Mundialis/commit/b4e2b80b22bb269965bd4ceb620d27b5658ea821))
* Add lightbox UI images ([017ee3](https://code.itinerare.net/itinerare/Mundialis/commit/017ee3ba1fc5405d0a3652d627a8b9fed72c49d5))
* Add settings config ([9ee9d3](https://code.itinerare.net/itinerare/Mundialis/commit/9ee9d324d4bb690c4a8f8bc6e7a64803f184986f))
* Track composer.lock ([c79b77](https://code.itinerare.net/itinerare/Mundialis/commit/c79b778ba108d78f051ca8aa99c006b911fc987e))
* Update composer.lock ([9a954a](https://code.itinerare.net/itinerare/Mundialis/commit/9a954aa23a6ae35b5736d816dcb09f846d166f1c))

##### Tests

* Add event/timeline tests ([68becc](https://code.itinerare.net/itinerare/Mundialis/commit/68becc11b5ce38b2ecfbb2059306b9f9535d2d87))
* Add initial access tests ([f197eb](https://code.itinerare.net/itinerare/Mundialis/commit/f197eb63e6442e752d467b3313ec8e6cbb9cecc3))
* Add page delete tests with images ([705a7e](https://code.itinerare.net/itinerare/Mundialis/commit/705a7e30bae965781b44c0ab7ce68d7440729648))
* Add page image delete tests ([50fd5d](https://code.itinerare.net/itinerare/Mundialis/commit/50fd5dba60be0abf32edbcb867ce6573c6e26832))
* Add page image update notif test ([d705e6](https://code.itinerare.net/itinerare/Mundialis/commit/d705e6db6592ca64490ade37f4f0aa8303a95ea9))
* Add page link tests, factory ([a9a874](https://code.itinerare.net/itinerare/Mundialis/commit/a9a87408f864557bd93299811f3430405e345a42))
* Add page watch tests ([8c7403](https://code.itinerare.net/itinerare/Mundialis/commit/8c7403b94ac2842701afa28ba1f39aa9b9c7fe44))
* Add subject category index tests ([e4eb5d](https://code.itinerare.net/itinerare/Mundialis/commit/e4eb5d610c34f032f392a54d5e980ec8a17a4cda))
* Add subject category view tests ([c58709](https://code.itinerare.net/itinerare/Mundialis/commit/c58709213ae51334794608d06eea2e2e1c5d8983))
* Add used invitation code auth test ([a4a0e6](https://code.itinerare.net/itinerare/Mundialis/commit/a4a0e67b15267bfb01147bba219ea0fe94124c37))
* Admin function tests ([60ec2e](https://code.itinerare.net/itinerare/Mundialis/commit/60ec2e7c2098fe2f3fa0c035f0d74452c02363dc))
* Auth and user function tests ([8534c1](https://code.itinerare.net/itinerare/Mundialis/commit/8534c13c3f4cb022d47c5d4063eb461c6168e556))
* Basic page delete/restore tests ([7e412a](https://code.itinerare.net/itinerare/Mundialis/commit/7e412adb945cb8282ba02a96cfd782ea1704f54b))
* Basic page tests ([3bcf27](https://code.itinerare.net/itinerare/Mundialis/commit/3bcf279513dd8e88cd1125af36d62f6c59f23ebb))
* Expand page image tests ([989088](https://code.itinerare.net/itinerare/Mundialis/commit/9890886ee216a925ee912a0e8c6bc9c442b35ac1))
* Expand user notification tests ([31397b](https://code.itinerare.net/itinerare/Mundialis/commit/31397b603ee0555157c3aa4f246d4d7c57cfa8f5))
* Expriment with using artisan test ([f4ba2f](https://code.itinerare.net/itinerare/Mundialis/commit/f4ba2f2959fd23ab606feb5ebf907e463a262f3d))
* Extend special page tests ([04598d](https://code.itinerare.net/itinerare/Mundialis/commit/04598d505f954fe50ecd0db5abf56f5603bff29a))
* File cleanup in image delete tests ([c02349](https://code.itinerare.net/itinerare/Mundialis/commit/c0234960bae4415636165dcf69cf2dc2a3c7a79b))
* Lexicon entry tests ([f70698](https://code.itinerare.net/itinerare/Mundialis/commit/f70698dda4da267deb0d38952a42553842ed510c))
* Page move tests ([790889](https://code.itinerare.net/itinerare/Mundialis/commit/790889e2f8b92ddb95a010ab13fe9570a5d817b7))
* Page protection tests, factory ([782117](https://code.itinerare.net/itinerare/Mundialis/commit/782117f91add6d652b474eb71c7af5d03f328bc2))
* Page relationship tests and factory ([92b3ed](https://code.itinerare.net/itinerare/Mundialis/commit/92b3ed0caf26829ad8d391252f5b34de74525460))
* Page view tests ([07d0a9](https://code.itinerare.net/itinerare/Mundialis/commit/07d0a9b32f0d7a4cf31749a79d38ba21f0db4c7b))
* Special and subject page tests ([5846f4](https://code.itinerare.net/itinerare/Mundialis/commit/5846f48b753febb80925bc6ce0b7e61eefdb5edd))
* Start of page image tests, factories ([a2d7d0](https://code.itinerare.net/itinerare/Mundialis/commit/a2d7d05dc859f18737db0e08310810e2e90461dd))
* Subject data tests ([7b77fb](https://code.itinerare.net/itinerare/Mundialis/commit/7b77fbacbaa8302ceefd461cb16858bd629a5929))

##### Time

* Add timeline display ([619fa5](https://code.itinerare.net/itinerare/Mundialis/commit/619fa57e724532e6aae7028143c94c16489f9cb1))
* Add 'use for dates' check to time divisions ([eda3bd](https://code.itinerare.net/itinerare/Mundialis/commit/eda3bd29f236e451731228a0f4804a97c8ee9319))
* Chronologies display ([9d54d5](https://code.itinerare.net/itinerare/Mundialis/commit/9d54d50fdc5b595953e81402dee78f9c92a71c51))
* Implement chronology & time divisions ([fd5caf](https://code.itinerare.net/itinerare/Mundialis/commit/fd5cafa33061912d800112754aa3f4c331df8b52))

##### Users

* Add user factory ([bd609f](https://code.itinerare.net/itinerare/Mundialis/commit/bd609f120d1f624f5df1e7c78908d76b9aa461e6))

### Bug Fixes

* Admin user routes clobber rank/invite ones ([eed999](https://code.itinerare.net/itinerare/Mundialis/commit/eed999f329872cdfa1d25ba638c43f75628e9754))
* Cannot upload site images ([699ab0](https://code.itinerare.net/itinerare/Mundialis/commit/699ab04115ca9de93aa647d0b949fa8d5b27c04c))
* Cascading field/widget template changes error ([0e7337](https://code.itinerare.net/itinerare/Mundialis/commit/0e73377344da24abc3b1fb58cc39b050eaddb16c))
* Categories indexes list view group incorrectly ([dd3759](https://code.itinerare.net/itinerare/Mundialis/commit/dd3759946e46ebf25a33b2eb9bea128af4a01f3b))
* Concepts typo ([b7d998](https://code.itinerare.net/itinerare/Mundialis/commit/b7d99828d150dc147f432c94464fbe4e0d2452ef))
* Error cascading some template changes ([898ddb](https://code.itinerare.net/itinerare/Mundialis/commit/898ddb1cbe6ec43119707600cf69911ad08bf6ed))
* Error cloning template rows ([080596](https://code.itinerare.net/itinerare/Mundialis/commit/080596ce17059266fe2d011749b245060ee160c7))
* Error deleting category with deleted pages ([8bfc78](https://code.itinerare.net/itinerare/Mundialis/commit/8bfc78b686745ad050c11688a30a82eaec0b219c))
* Error deleting subject categories ([813a86](https://code.itinerare.net/itinerare/Mundialis/commit/813a86ddf41bb32537cbcda51bae75a426ce48ce))
* Error editing subject categories ([b35834](https://code.itinerare.net/itinerare/Mundialis/commit/b3583453a9e39db22956e208bdf575298392e07d))
* Infobox 'add field' button at top ([a5c78a](https://code.itinerare.net/itinerare/Mundialis/commit/a5c78a70dc9913da1eb0f267187fcdeb5208002e))
* Infobox field validation rule issues ([4891b9](https://code.itinerare.net/itinerare/Mundialis/commit/4891b91d97ee40b61a08689069b830804c35bf91))
* Issue detecting field changes as removals ([47abc8](https://code.itinerare.net/itinerare/Mundialis/commit/47abc871203cb90ac8f7bc2c2d0ead3fef0c0cc4))
* Lang, time page titles nonspecific ([f14180](https://code.itinerare.net/itinerare/Mundialis/commit/f14180fdbd0b6d8bfd71cf56ad6e6f82d5938f9b))
* Logo present in mobile sidebar ([a199cc](https://code.itinerare.net/itinerare/Mundialis/commit/a199cc6ed5e391eed9aaefdcd5312c0d0d292119))
* Minor template builder issues ([7fe693](https://code.itinerare.net/itinerare/Mundialis/commit/7fe6937a102e704ba3c6bbd19f05116637497de1))
* Misc tidying and fixing ([434e5b](https://code.itinerare.net/itinerare/Mundialis/commit/434e5b79145c906d295f6ef231f0ce9aea13abb2))
* Read check errors when logged out ([3ec976](https://code.itinerare.net/itinerare/Mundialis/commit/3ec976c9eba1a67eef485931f53853259afd3a80))
* Removing template section removes all of them ([78ad4a](https://code.itinerare.net/itinerare/Mundialis/commit/78ad4a6110e8db9e9d410a4d47ca97acc0da814f))
* Section 'add field' button also at top ([2efc74](https://code.itinerare.net/itinerare/Mundialis/commit/2efc7427fcb020d9bfbca279bbf94f09fc8e1b24))
* Setup-admin reset out of order ([485b2a](https://code.itinerare.net/itinerare/Mundialis/commit/485b2af675edb0a2bb406a26494cad51cadca39f))
* TinyMCE won't initialize ([c2370a](https://code.itinerare.net/itinerare/Mundialis/commit/c2370aa4fb0ef2538f3493f5ae35440509eb2a87))
* Typo in subjects config file ([0d7fa2](https://code.itinerare.net/itinerare/Mundialis/commit/0d7fa2527a25716155025ac055fef64dbf88434b))
* Update command not declared properly ([90beda](https://code.itinerare.net/itinerare/Mundialis/commit/90beda5868c93664505cee6106ac780e9c8bca54))
* Validation errors do not appear ([30330c](https://code.itinerare.net/itinerare/Mundialis/commit/30330c4946afc24c0a0319f0fba542bc981bed3c))

##### Auth

* Extraneous user settings alert ([8ff4b5](https://code.itinerare.net/itinerare/Mundialis/commit/8ff4b5d8b6625823f731938d2cbe073429aa8b68))
* Watched pages title incorrect ([618670](https://code.itinerare.net/itinerare/Mundialis/commit/618670adeccadeb5f830b62cd33506f65d2158ba))

##### Dep

* Composer.json version string ([7ac90a](https://code.itinerare.net/itinerare/Mundialis/commit/7ac90a9461180d792f679caa4a2d09d9a7b04cfc))

##### Lang

* Add missing auth check to entry index ([d2baa2](https://code.itinerare.net/itinerare/Mundialis/commit/d2baa277cd67c8b25d88e1779b0902e6306c4e4e))
* Can't edit categories w/o properties ([7f09cc](https://code.itinerare.net/itinerare/Mundialis/commit/7f09ccbe2af4dc292fe9a8b02849850274dc4506))
* Category conj/declen order inconsistent ([3279f1](https://code.itinerare.net/itinerare/Mundialis/commit/3279f156d95ddc0b34e7786a5729efce6f868196))
* Category deletion uses wrong function ([0e2583](https://code.itinerare.net/itinerare/Mundialis/commit/0e2583658f74d1da932d4523f203ae83a8df772d))
* Category edit button links to wrong page ([b3ecc6](https://code.itinerare.net/itinerare/Mundialis/commit/b3ecc6fabffa0ad4af4ac19f75b856a7ff7651b5))
* Category table display wonky ([603464](https://code.itinerare.net/itinerare/Mundialis/commit/603464d222f2306035bebbff4c500beec7012f7d))
* Creation of empty etymology records ([aa6e42](https://code.itinerare.net/itinerare/Mundialis/commit/aa6e42f8dcddbbad9f22af381229f7edd667ae31))
* Errant dd() ([3a0240](https://code.itinerare.net/itinerare/Mundialis/commit/3a02406ebadc59238e64ba2209f6afd7b2548a74))
* Error creating/editing entries ([51f80a](https://code.itinerare.net/itinerare/Mundialis/commit/51f80ad112d8aad03e76c00f62b9bfe4b2269e5f))
* Error editing entries w/o conj/decl ([326d55](https://code.itinerare.net/itinerare/Mundialis/commit/326d5525cd1e8ec1341f879fca0b1b9f54ee3285))
* Error updating entry etymology ([be922f](https://code.itinerare.net/itinerare/Mundialis/commit/be922f8112f0000cdd1f3dcae7332111ed29d699))
* Error viewing categoryless entries ([8bd4ed](https://code.itinerare.net/itinerare/Mundialis/commit/8bd4edec1bf17cddc65065c51b492d5bb343463c))
* Etymology entry def is all lowercase ([fdae6e](https://code.itinerare.net/itinerare/Mundialis/commit/fdae6e13025883424ab02d27db6b24db80ca7af2))
* Etymology shows own lex class ([721987](https://code.itinerare.net/itinerare/Mundialis/commit/721987e590ab654d9d4206c920b83c095b5d7572))
* Issue w conj/decl autogen ([3eb780](https://code.itinerare.net/itinerare/Mundialis/commit/3eb7803e1ab5c2b3937de8897ab67fe97edea7f2))
* New entry parsed description not saved ([0ffdcc](https://code.itinerare.net/itinerare/Mundialis/commit/0ffdcc3e9a835530e6ec3c9c897d02ed0cd89fc0))
* Wiki link parse error on create entry ([8b9b03](https://code.itinerare.net/itinerare/Mundialis/commit/8b9b03ca5a58ecd35e1b5e2e4e42ec4b12a66399))

##### Language

* More checking on entry deletion ([39e83f](https://code.itinerare.net/itinerare/Mundialis/commit/39e83fa589c2c059a1c80edc5ca7dc00603c7608))

##### Pages

* Add check for image page attachment ([43c4e4](https://code.itinerare.net/itinerare/Mundialis/commit/43c4e4dacc0a4875f4effb7761e5b6dcd31e12ac))
* Allow dashes in parsed links ([ec5b6f](https://code.itinerare.net/itinerare/Mundialis/commit/ec5b6f149d1bd79709196fd76d5c45e47858338e))
* Auto-ToC width wonky on mobile ([c629f8](https://code.itinerare.net/itinerare/Mundialis/commit/c629f88362d61173ca7fd70a83c46aef402217bc))
* Better relationship creation checks ([b4f8d9](https://code.itinerare.net/itinerare/Mundialis/commit/b4f8d96efcdf6b239f9086af5bec8ca4d9f84259))
* Cannot remove extra links from images ([7d031e](https://code.itinerare.net/itinerare/Mundialis/commit/7d031e747232b1435855c1f83e67bf9549a15f23))
* Cannot set page non-visible ([578712](https://code.itinerare.net/itinerare/Mundialis/commit/57871216fc47757ae8da22fc382ed2b5302909f0))
* Category optgroups sorted wrong ([35e1ed](https://code.itinerare.net/itinerare/Mundialis/commit/35e1ed905c03b7e532831f2c537db2786d56bd63))
* Check image has pages when restoring ([c91f53](https://code.itinerare.net/itinerare/Mundialis/commit/c91f53632a8245fd0052813b3e306784bf17cb35))
* Create image active toggle not shown ([ee8dfa](https://code.itinerare.net/itinerare/Mundialis/commit/ee8dfab142b471e30c947e5e0dc73dc514c52c70))
* Current page is in image edit pages ([496ee7](https://code.itinerare.net/itinerare/Mundialis/commit/496ee7aebdd3e73992a4ffdfb6a69a247cfdd0c7))
* Data column not nullable ([b7b0ed](https://code.itinerare.net/itinerare/Mundialis/commit/b7b0ed9b4159cc1f4caa711a8fb0cb781390219e))
* Deleted page/image results in recent edits ([4c71f8](https://code.itinerare.net/itinerare/Mundialis/commit/4c71f88413de4637b7e1b0f8662bc37a79ffb5a2))
* DisplayName formatting ([9bbfe7](https://code.itinerare.net/itinerare/Mundialis/commit/9bbfe7efcc21ce8a7d920615d7fe1df85424ccac))
* DisplayName formatting error ([fbba2f](https://code.itinerare.net/itinerare/Mundialis/commit/fbba2f178185a3e1d77d1ea5600c755b12cbee2f))
* Edit/reset allow deleted page title ([aec9de](https://code.itinerare.net/itinerare/Mundialis/commit/aec9de61db75f8138e66349f9e2269ab608324c4))
* Error creating pages ([de4c42](https://code.itinerare.net/itinerare/Mundialis/commit/de4c42a86f9ce6d58415441625830459cf8c3871))
* Error fetching page data from version ([3e6ccb](https://code.itinerare.net/itinerare/Mundialis/commit/3e6ccb26d0f3517797f04a45609dbebe36f8ff85))
* Error restoring page ([08bce5](https://code.itinerare.net/itinerare/Mundialis/commit/08bce56cacb89aef5fde51e6313a2b4778a88d05))
* Error uploading custom image thumbnail ([faae12](https://code.itinerare.net/itinerare/Mundialis/commit/faae127be0d22a56a7fd7844280593c1ba20c5eb))
* Error viewing categories ([878c97](https://code.itinerare.net/itinerare/Mundialis/commit/878c97f681eac8d140dd0d9a330655b673c16991))
* Error viewing recent page/image widget ([b387a1](https://code.itinerare.net/itinerare/Mundialis/commit/b387a19d40a8ca0c7fbebc7cde367d62a62d7006))
* Force-deleted pages error changes list ([98f958](https://code.itinerare.net/itinerare/Mundialis/commit/98f95886d6b1a3e2f59c28fa8a3ea759dced6279))
* Gallery formatting slightly wonky ([1153b4](https://code.itinerare.net/itinerare/Mundialis/commit/1153b4c9ef608a94e8fb5c07e03301eff5a16987))
* Handle link parse special chars better ([3bc7e2](https://code.itinerare.net/itinerare/Mundialis/commit/3bc7e24babac2513101e0e8624a511cbbbe15713))
* Hidden pages visible in navbox ([52b7db](https://code.itinerare.net/itinerare/Mundialis/commit/52b7db8580b1f3bdb413732a83640edfbd206634))
* Image create/edit verbiage, processing ([e82058](https://code.itinerare.net/itinerare/Mundialis/commit/e82058618c807037afbe48e3fb630d08bfbab3ec))
* Image files aren't deleted properly ([f7380d](https://code.itinerare.net/itinerare/Mundialis/commit/f7380d6aa6be16884f54ea6a001a9da6ebc89fc5))
* Image height in popup not capped ([b6fed7](https://code.itinerare.net/itinerare/Mundialis/commit/b6fed7953b0a2d0ff8a75f91d9c5b24bc273a759))
* Image info formatting tweak ([543ee8](https://code.itinerare.net/itinerare/Mundialis/commit/543ee8136b44bc5b3a84dc22b351697aba681594))
* Image revision log takes up more space ([e45865](https://code.itinerare.net/itinerare/Mundialis/commit/e458651c373beee3412602f8448f37799d75157e))
* Images in info popup/page not centered ([9282ca](https://code.itinerare.net/itinerare/Mundialis/commit/9282cadad8fcb28e8ed5ac31c804ff04d7d6e272))
* Image unlink deletes page not detaches ([d3ae71](https://code.itinerare.net/itinerare/Mundialis/commit/d3ae71900e32311387ad8d81c34f13f8ba73feab))
* Introduction crowds infobox ([8c6c3e](https://code.itinerare.net/itinerare/Mundialis/commit/8c6c3e1ad293de539fdb8fc116a3cebd90eab28b))
* Issue setting image creator url ([8f5d34](https://code.itinerare.net/itinerare/Mundialis/commit/8f5d347a77023c2ae6fe87041f7055f0d7d75c11))
* Link parse detection issue, dupe links ([72c475](https://code.itinerare.net/itinerare/Mundialis/commit/72c4751a8d1118ce4c272acd7484494d1f45b1d1))
* Link parse issues for people, disambig ([ab7d77](https://code.itinerare.net/itinerare/Mundialis/commit/ab7d77b2275c79573bd3eab1833a84c816160ac3))
* Link parsing error ([142392](https://code.itinerare.net/itinerare/Mundialis/commit/14239233a77b1e1b0c1ae06c247d52b81d7d1438))
* Minor infobox field display issue ([7784fb](https://code.itinerare.net/itinerare/Mundialis/commit/7784fb4955a51d2f05c2018e1b2dcc38ac50aec1))
* Minor whitespace adjustment to service ([020d75](https://code.itinerare.net/itinerare/Mundialis/commit/020d754f58eb70d9f17f24a796ee35111604e4e4))
* More consistent img creator processing ([4afabc](https://code.itinerare.net/itinerare/Mundialis/commit/4afabc8b80c72de39d1b7a30fba3957ed8cc6ba9))
* Navbox displays deleted pages ([251163](https://code.itinerare.net/itinerare/Mundialis/commit/2511635fa9d858825a365025cf0ebe2433adf3f5))
* Navbox doesn't display subcat pages... ([8329fb](https://code.itinerare.net/itinerare/Mundialis/commit/8329fb11fe7f7c8dec1d72011c2b5701cc3f06df))
* Navbox errors when no context pages ([486c47](https://code.itinerare.net/itinerare/Mundialis/commit/486c4718f01da90c02cb114892c21b3613a112ff))
* Page image update notif not being sent ([621c30](https://code.itinerare.net/itinerare/Mundialis/commit/621c301762442b8450db361f748bac74ca1a2988))
* Page index top pagination out of order ([9cf99b](https://code.itinerare.net/itinerare/Mundialis/commit/9cf99b96d51a620482199b5e92079f909c2d9e31))
* Page link detection broken ([2f1e45](https://code.itinerare.net/itinerare/Mundialis/commit/2f1e45a8c777dd526be7bda3521fdb8c99080957))
* Page list cols try to nest ([a9d334](https://code.itinerare.net/itinerare/Mundialis/commit/a9d3344243cbd84e44ec738fb33b8ec2274d62c2))
* Parent place field populated wrong ([511604](https://code.itinerare.net/itinerare/Mundialis/commit/51160477defeea2b3c8d6c79396ad600e275aeec))
* Parsed link inconsistent w displayName ([8fc3b3](https://code.itinerare.net/itinerare/Mundialis/commit/8fc3b3968a64b0d6d1a8440ed67d0ed8c59a42ce))
* Parsed links don't display in infobox ([a59195](https://code.itinerare.net/itinerare/Mundialis/commit/a59195ea51eff9c16ee3bded1ce04827faecb8eb))
* Random page errors when no pages ([a4056d](https://code.itinerare.net/itinerare/Mundialis/commit/a4056d5b13f4a63045dd5b51de33352d154a59ce))
* Relationships not force deleted w page ([f5323d](https://code.itinerare.net/itinerare/Mundialis/commit/f5323d12ddba580b82be43e2b7d868b4aa8b3d5a))
* Routing error ([893f3a](https://code.itinerare.net/itinerare/Mundialis/commit/893f3a43f30f52ddb571ec9fb2ffe13f17e055af))
* Section length calc doesn't add right ([904a6a](https://code.itinerare.net/itinerare/Mundialis/commit/904a6a1bcce147ac7f100bc749bf68cc0af58cb1))
* Subject breadcrumbs links outdated ([c86294](https://code.itinerare.net/itinerare/Mundialis/commit/c862949f09b079afc0bb0be12a84a5704f332faa))
* Subject query inexplicably hardcoded ([b8caa1](https://code.itinerare.net/itinerare/Mundialis/commit/b8caa1cf938e7278c69909ecd93b6903fcd5ecb1))
* Subject routes not adjusted ([835e57](https://code.itinerare.net/itinerare/Mundialis/commit/835e57f39782871006f1a5baa21aae9ea8dd48f8))
* Tag field not labeled optional ([d0b508](https://code.itinerare.net/itinerare/Mundialis/commit/d0b5087ff68673a99ea8c58e1af01be3196122b7))
* Timeline errors if no date divisions ([df67bd](https://code.itinerare.net/itinerare/Mundialis/commit/df67bd5d7f68cedcc1673ff963c0caaff5d2405d))
* Undo overzealous change ([89e352](https://code.itinerare.net/itinerare/Mundialis/commit/89e352ee0fccfc53d9829484a51efc0722ab2593))
* Version data displaying incorrectly ([532103](https://code.itinerare.net/itinerare/Mundialis/commit/532103cccbbb3c807d96c7ed602ceb0e4da193db))
* Wiki link parsing backwards ([751804](https://code.itinerare.net/itinerare/Mundialis/commit/751804f6670f913265f368018595c0a0d446412f))

##### People

* Error viewing relationships ([db68ae](https://code.itinerare.net/itinerare/Mundialis/commit/db68ae25cc34e667163821c537edd23d9867ec89))
* Incorrect form order when creating relationships ([6d1b48](https://code.itinerare.net/itinerare/Mundialis/commit/6d1b48a3775078ebd950e7975b0753b272d3512b))

##### Resources

* Changelog command has wrong description ([ff1859](https://code.itinerare.net/itinerare/Mundialis/commit/ff1859528074ce1d44be37ea75e3ea725890a803))

##### Tests

* Add failsafe site page generation ([c3e5e7](https://code.itinerare.net/itinerare/Mundialis/commit/c3e5e7491225e271b4b0d99a580680d53a934dce))
* Error running basic feature test ([748af1](https://code.itinerare.net/itinerare/Mundialis/commit/748af1d0b1b40922ab5c5dee9c96887388bfaa69))
* Error running tests ([b9cbbf](https://code.itinerare.net/itinerare/Mundialis/commit/b9cbbfd214b8827e5211206dae0286f3be8463ed))
* Error running tests on fresh DB ([4658d0](https://code.itinerare.net/itinerare/Mundialis/commit/4658d0556021bdff7bb8a099300eea7333276f45))
* Image update notif test error ([6581ce](https://code.itinerare.net/itinerare/Mundialis/commit/6581ce59ebd0fea894a9b2d6ef18949b56143286))
* Remove unit test, since currently empty ([a796b1](https://code.itinerare.net/itinerare/Mundialis/commit/a796b1bdbc1e39e2da04da59e7ee9f5665c6c31b))

##### Time

* Chronology editing points at category editing ([20b89f](https://code.itinerare.net/itinerare/Mundialis/commit/20b89f4f954b3bdc27cdfe75f3c8841b15dcb1a0))
* Chronology index table displays wonky ([086068](https://code.itinerare.net/itinerare/Mundialis/commit/086068a5aada957adb6078f1eb9c485339ddd6f0))
* Error sorting chronologies ([541150](https://code.itinerare.net/itinerare/Mundialis/commit/54115014b2f62220236bb463e28fe9455906d04d))
* Fix error editing divisions ([68ef60](https://code.itinerare.net/itinerare/Mundialis/commit/68ef605bab10629347d4778ff32e8f1d437e0507))
* Renaming time divisions would delete/recreate ([98760d](https://code.itinerare.net/itinerare/Mundialis/commit/98760d898ea0f25d38dbf58c6aa3903b4623e85a))

##### Users

* 2FA confirm/disable functions absent ([2e1e89](https://code.itinerare.net/itinerare/Mundialis/commit/2e1e89283bce4490227996d91b208f61d506402c))
* Add check for closed reg when creating ([fd2b25](https://code.itinerare.net/itinerare/Mundialis/commit/fd2b2502927a4d383979b464139775fffc7949ab))
* Cleaner canWrite, admin checks ([d2781c](https://code.itinerare.net/itinerare/Mundialis/commit/d2781cd4c1740b5999d42aa6e616f950820ff8f7))
* Error banning user ([696001](https://code.itinerare.net/itinerare/Mundialis/commit/6960011f2e699f74698b30815c98ad1d6889e78f))
* Error clearing notifications of type 0 ([49ded1](https://code.itinerare.net/itinerare/Mundialis/commit/49ded1d10314a705967f86821724c0c67cfb1bf7))
* Error unbanning user ([c45f25](https://code.itinerare.net/itinerare/Mundialis/commit/c45f25c9ac8e8daa979b123a49b28fb74273e563))
* Further improved rank checks/fetching ([205f48](https://code.itinerare.net/itinerare/Mundialis/commit/205f4872a1a34065c9579e987b6599f65f5f9fee))
* Incorrect account sidebar link ([1a56a1](https://code.itinerare.net/itinerare/Mundialis/commit/1a56a16398aa4ea61027488804acd54107d0a460))
* More consistent canWrite check ([b6dcd7](https://code.itinerare.net/itinerare/Mundialis/commit/b6dcd76bd0c5385bd57da651144c8e35cbce1de2))
* Recent images preview error on profile ([7a1396](https://code.itinerare.net/itinerare/Mundialis/commit/7a139625214fa1a777a83e92778c63e616bf3673))

---

## [1.0.0-rc1](https://code.itinerare.net/itinerare/Mundialis/compare/1.0.0-pre4...v1.0.0-rc1) (2021-11-23)
### Features


##### Tests

* Add event/timeline tests ([68becc](https://code.itinerare.net/itinerare/Mundialis/commit/68becc11b5ce38b2ecfbb2059306b9f9535d2d87))
* Add initial access tests ([f197eb](https://code.itinerare.net/itinerare/Mundialis/commit/f197eb63e6442e752d467b3313ec8e6cbb9cecc3))
* Add page delete tests with images ([705a7e](https://code.itinerare.net/itinerare/Mundialis/commit/705a7e30bae965781b44c0ab7ce68d7440729648))
* Add page image delete tests ([50fd5d](https://code.itinerare.net/itinerare/Mundialis/commit/50fd5dba60be0abf32edbcb867ce6573c6e26832))
* Add page image update notif test ([d705e6](https://code.itinerare.net/itinerare/Mundialis/commit/d705e6db6592ca64490ade37f4f0aa8303a95ea9))
* Add page link tests, factory ([a9a874](https://code.itinerare.net/itinerare/Mundialis/commit/a9a87408f864557bd93299811f3430405e345a42))
* Add page watch tests ([8c7403](https://code.itinerare.net/itinerare/Mundialis/commit/8c7403b94ac2842701afa28ba1f39aa9b9c7fe44))
* Add subject category index tests ([e4eb5d](https://code.itinerare.net/itinerare/Mundialis/commit/e4eb5d610c34f032f392a54d5e980ec8a17a4cda))
* Add subject category view tests ([c58709](https://code.itinerare.net/itinerare/Mundialis/commit/c58709213ae51334794608d06eea2e2e1c5d8983))
* Add used invitation code auth test ([a4a0e6](https://code.itinerare.net/itinerare/Mundialis/commit/a4a0e67b15267bfb01147bba219ea0fe94124c37))
* Admin function tests ([60ec2e](https://code.itinerare.net/itinerare/Mundialis/commit/60ec2e7c2098fe2f3fa0c035f0d74452c02363dc))
* Auth and user function tests ([8534c1](https://code.itinerare.net/itinerare/Mundialis/commit/8534c13c3f4cb022d47c5d4063eb461c6168e556))
* Basic page delete/restore tests ([7e412a](https://code.itinerare.net/itinerare/Mundialis/commit/7e412adb945cb8282ba02a96cfd782ea1704f54b))
* Basic page tests ([3bcf27](https://code.itinerare.net/itinerare/Mundialis/commit/3bcf279513dd8e88cd1125af36d62f6c59f23ebb))
* Expand page image tests ([989088](https://code.itinerare.net/itinerare/Mundialis/commit/9890886ee216a925ee912a0e8c6bc9c442b35ac1))
* Expand user notification tests ([31397b](https://code.itinerare.net/itinerare/Mundialis/commit/31397b603ee0555157c3aa4f246d4d7c57cfa8f5))
* Expriment with using artisan test ([f4ba2f](https://code.itinerare.net/itinerare/Mundialis/commit/f4ba2f2959fd23ab606feb5ebf907e463a262f3d))
* Extend special page tests ([04598d](https://code.itinerare.net/itinerare/Mundialis/commit/04598d505f954fe50ecd0db5abf56f5603bff29a))
* File cleanup in image delete tests ([c02349](https://code.itinerare.net/itinerare/Mundialis/commit/c0234960bae4415636165dcf69cf2dc2a3c7a79b))
* Lexicon entry tests ([f70698](https://code.itinerare.net/itinerare/Mundialis/commit/f70698dda4da267deb0d38952a42553842ed510c))
* Page move tests ([790889](https://code.itinerare.net/itinerare/Mundialis/commit/790889e2f8b92ddb95a010ab13fe9570a5d817b7))
* Page protection tests, factory ([782117](https://code.itinerare.net/itinerare/Mundialis/commit/782117f91add6d652b474eb71c7af5d03f328bc2))
* Page relationship tests and factory ([92b3ed](https://code.itinerare.net/itinerare/Mundialis/commit/92b3ed0caf26829ad8d391252f5b34de74525460))
* Page view tests ([07d0a9](https://code.itinerare.net/itinerare/Mundialis/commit/07d0a9b32f0d7a4cf31749a79d38ba21f0db4c7b))
* Special and subject page tests ([5846f4](https://code.itinerare.net/itinerare/Mundialis/commit/5846f48b753febb80925bc6ce0b7e61eefdb5edd))
* Start of page image tests, factories ([a2d7d0](https://code.itinerare.net/itinerare/Mundialis/commit/a2d7d05dc859f18737db0e08310810e2e90461dd))
* Subject data tests ([7b77fb](https://code.itinerare.net/itinerare/Mundialis/commit/7b77fbacbaa8302ceefd461cb16858bd629a5929))

##### Users

* Add user factory ([bd609f](https://code.itinerare.net/itinerare/Mundialis/commit/bd609f120d1f624f5df1e7c78908d76b9aa461e6))

### Bug Fixes

* Error cascading some template changes ([898ddb](https://code.itinerare.net/itinerare/Mundialis/commit/898ddb1cbe6ec43119707600cf69911ad08bf6ed))
* Error deleting category with deleted pages ([8bfc78](https://code.itinerare.net/itinerare/Mundialis/commit/8bfc78b686745ad050c11688a30a82eaec0b219c))
* Misc tidying and fixing ([434e5b](https://code.itinerare.net/itinerare/Mundialis/commit/434e5b79145c906d295f6ef231f0ce9aea13abb2))

##### Language

* More checking on entry deletion ([39e83f](https://code.itinerare.net/itinerare/Mundialis/commit/39e83fa589c2c059a1c80edc5ca7dc00603c7608))

##### Pages

* Add check for image page attachment ([43c4e4](https://code.itinerare.net/itinerare/Mundialis/commit/43c4e4dacc0a4875f4effb7761e5b6dcd31e12ac))
* Better relationship creation checks ([b4f8d9](https://code.itinerare.net/itinerare/Mundialis/commit/b4f8d96efcdf6b239f9086af5bec8ca4d9f84259))
* DisplayName formatting ([9bbfe7](https://code.itinerare.net/itinerare/Mundialis/commit/9bbfe7efcc21ce8a7d920615d7fe1df85424ccac))
* DisplayName formatting error ([fbba2f](https://code.itinerare.net/itinerare/Mundialis/commit/fbba2f178185a3e1d77d1ea5600c755b12cbee2f))
* Error uploading custom image thumbnail ([faae12](https://code.itinerare.net/itinerare/Mundialis/commit/faae127be0d22a56a7fd7844280593c1ba20c5eb))
* Image create/edit verbiage, processing ([e82058](https://code.itinerare.net/itinerare/Mundialis/commit/e82058618c807037afbe48e3fb630d08bfbab3ec))
* Issue setting image creator url ([8f5d34](https://code.itinerare.net/itinerare/Mundialis/commit/8f5d347a77023c2ae6fe87041f7055f0d7d75c11))
* Minor whitespace adjustment to service ([020d75](https://code.itinerare.net/itinerare/Mundialis/commit/020d754f58eb70d9f17f24a796ee35111604e4e4))
* Page image update notif not being sent ([621c30](https://code.itinerare.net/itinerare/Mundialis/commit/621c301762442b8450db361f748bac74ca1a2988))
* Parsed link inconsistent w displayName ([8fc3b3](https://code.itinerare.net/itinerare/Mundialis/commit/8fc3b3968a64b0d6d1a8440ed67d0ed8c59a42ce))
* Relationships not force deleted w page ([f5323d](https://code.itinerare.net/itinerare/Mundialis/commit/f5323d12ddba580b82be43e2b7d868b4aa8b3d5a))
* Timeline errors if no date divisions ([df67bd](https://code.itinerare.net/itinerare/Mundialis/commit/df67bd5d7f68cedcc1673ff963c0caaff5d2405d))
* Undo overzealous change ([89e352](https://code.itinerare.net/itinerare/Mundialis/commit/89e352ee0fccfc53d9829484a51efc0722ab2593))

##### Tests

* Add failsafe site page generation ([c3e5e7](https://code.itinerare.net/itinerare/Mundialis/commit/c3e5e7491225e271b4b0d99a580680d53a934dce))
* Error running tests on fresh DB ([4658d0](https://code.itinerare.net/itinerare/Mundialis/commit/4658d0556021bdff7bb8a099300eea7333276f45))
* Image update notif test error ([6581ce](https://code.itinerare.net/itinerare/Mundialis/commit/6581ce59ebd0fea894a9b2d6ef18949b56143286))

##### Users

* 2FA confirm/disable functions absent ([2e1e89](https://code.itinerare.net/itinerare/Mundialis/commit/2e1e89283bce4490227996d91b208f61d506402c))
* Add check for closed reg when creating ([fd2b25](https://code.itinerare.net/itinerare/Mundialis/commit/fd2b2502927a4d383979b464139775fffc7949ab))
* Error banning user ([696001](https://code.itinerare.net/itinerare/Mundialis/commit/6960011f2e699f74698b30815c98ad1d6889e78f))
* Error clearing notifications of type 0 ([49ded1](https://code.itinerare.net/itinerare/Mundialis/commit/49ded1d10314a705967f86821724c0c67cfb1bf7))
* Error unbanning user ([c45f25](https://code.itinerare.net/itinerare/Mundialis/commit/c45f25c9ac8e8daa979b123a49b28fb74273e563))
* Further improved rank checks/fetching ([205f48](https://code.itinerare.net/itinerare/Mundialis/commit/205f4872a1a34065c9579e987b6599f65f5f9fee))
* Incorrect account sidebar link ([1a56a1](https://code.itinerare.net/itinerare/Mundialis/commit/1a56a16398aa4ea61027488804acd54107d0a460))

---

## [1.0.0-pre4](https://code.itinerare.net/itinerare/Mundialis/compare/1.0.0-pre3...1.0.0-pre4) (2021-10-10)
### Bug Fixes


##### Dep

* Composer.json version string ([7ac90a](https://code.itinerare.net/itinerare/Mundialis/commit/7ac90a9461180d792f679caa4a2d09d9a7b04cfc))

##### Pages

* Minor infobox field display issue ([7784fb](https://code.itinerare.net/itinerare/Mundialis/commit/7784fb4955a51d2f05c2018e1b2dcc38ac50aec1))

##### Tests

* Error running basic feature test ([748af1](https://code.itinerare.net/itinerare/Mundialis/commit/748af1d0b1b40922ab5c5dee9c96887388bfaa69))
* Error running tests ([b9cbbf](https://code.itinerare.net/itinerare/Mundialis/commit/b9cbbfd214b8827e5211206dae0286f3be8463ed))
* Remove unit test, since currently empty ([a796b1](https://code.itinerare.net/itinerare/Mundialis/commit/a796b1bdbc1e39e2da04da59e7ee9f5665c6c31b))

##### Users

* Cleaner canWrite, admin checks ([d2781c](https://code.itinerare.net/itinerare/Mundialis/commit/d2781cd4c1740b5999d42aa6e616f950820ff8f7))
* More consistent canWrite check ([b6dcd7](https://code.itinerare.net/itinerare/Mundialis/commit/b6dcd76bd0c5385bd57da651144c8e35cbce1de2))
* Recent images preview error on profile ([7a1396](https://code.itinerare.net/itinerare/Mundialis/commit/7a139625214fa1a777a83e92778c63e616bf3673))

---

## [1.0.0-pre3](https://code.itinerare.net/itinerare/Mundialis/compare/1.0.0-pre2...1.0.0-pre3) (2021-08-22)
### Bug Fixes


##### Pages

* Create image active toggle not shown ([ee8dfa](https://code.itinerare.net/itinerare/Mundialis/commit/ee8dfab142b471e30c947e5e0dc73dc514c52c70))
* Image height in popup not capped ([b6fed7](https://code.itinerare.net/itinerare/Mundialis/commit/b6fed7953b0a2d0ff8a75f91d9c5b24bc273a759))

---

## [1.0.0-pre2](https://code.itinerare.net/itinerare/Mundialis/compare/1.0.0-pre1...1.0.0-pre2) (2021-08-15)
### Features

* Add further .env files to ignore ([532f94](https://code.itinerare.net/itinerare/Mundialis/commit/532f9445788430018e139fa6673e007e2e0d6010))
* Tos/privacy policy pages/fix them ([7d7480](https://code.itinerare.net/itinerare/Mundialis/commit/7d7480430bbebabea901b86edd647db06ace41db))

##### Auth

* Adjust user/acc page title formatting ([dae788](https://code.itinerare.net/itinerare/Mundialis/commit/dae788805c687a7d0c118cf8fce546e5e6d8f9e6))

##### Lang

* Capitalize ucfirst()'d entry autoconj ([6f99e2](https://code.itinerare.net/itinerare/Mundialis/commit/6f99e2089148c12048f83501d75869e549055906))

##### Resources

* Add cache clear to update command ([4a31a7](https://code.itinerare.net/itinerare/Mundialis/commit/4a31a7d62745d494b84ef048d0f5a4917a143e57))
* Update composer.lock ([9a954a](https://code.itinerare.net/itinerare/Mundialis/commit/9a954aa23a6ae35b5736d816dcb09f846d166f1c))

### Bug Fixes

* Cannot upload site images ([699ab0](https://code.itinerare.net/itinerare/Mundialis/commit/699ab04115ca9de93aa647d0b949fa8d5b27c04c))
* Categories indexes list view group incorrectly ([dd3759](https://code.itinerare.net/itinerare/Mundialis/commit/dd3759946e46ebf25a33b2eb9bea128af4a01f3b))
* Error cloning template rows ([080596](https://code.itinerare.net/itinerare/Mundialis/commit/080596ce17059266fe2d011749b245060ee160c7))
* Error deleting subject categories ([813a86](https://code.itinerare.net/itinerare/Mundialis/commit/813a86ddf41bb32537cbcda51bae75a426ce48ce))
* Infobox 'add field' button at top ([a5c78a](https://code.itinerare.net/itinerare/Mundialis/commit/a5c78a70dc9913da1eb0f267187fcdeb5208002e))
* Infobox field validation rule issues ([4891b9](https://code.itinerare.net/itinerare/Mundialis/commit/4891b91d97ee40b61a08689069b830804c35bf91))
* Lang, time page titles nonspecific ([f14180](https://code.itinerare.net/itinerare/Mundialis/commit/f14180fdbd0b6d8bfd71cf56ad6e6f82d5938f9b))
* Read check errors when logged out ([3ec976](https://code.itinerare.net/itinerare/Mundialis/commit/3ec976c9eba1a67eef485931f53853259afd3a80))
* Section 'add field' button also at top ([2efc74](https://code.itinerare.net/itinerare/Mundialis/commit/2efc7427fcb020d9bfbca279bbf94f09fc8e1b24))

##### Auth

* Extraneous user settings alert ([8ff4b5](https://code.itinerare.net/itinerare/Mundialis/commit/8ff4b5d8b6625823f731938d2cbe073429aa8b68))
* Watched pages title incorrect ([618670](https://code.itinerare.net/itinerare/Mundialis/commit/618670adeccadeb5f830b62cd33506f65d2158ba))

##### Lang

* Errant dd() ([3a0240](https://code.itinerare.net/itinerare/Mundialis/commit/3a02406ebadc59238e64ba2209f6afd7b2548a74))
* Error creating/editing entries ([51f80a](https://code.itinerare.net/itinerare/Mundialis/commit/51f80ad112d8aad03e76c00f62b9bfe4b2269e5f))
* Error editing entries w/o conj/decl ([326d55](https://code.itinerare.net/itinerare/Mundialis/commit/326d5525cd1e8ec1341f879fca0b1b9f54ee3285))
* Error updating entry etymology ([be922f](https://code.itinerare.net/itinerare/Mundialis/commit/be922f8112f0000cdd1f3dcae7332111ed29d699))
* Error viewing categoryless entries ([8bd4ed](https://code.itinerare.net/itinerare/Mundialis/commit/8bd4edec1bf17cddc65065c51b492d5bb343463c))
* Etymology entry def is all lowercase ([fdae6e](https://code.itinerare.net/itinerare/Mundialis/commit/fdae6e13025883424ab02d27db6b24db80ca7af2))
* Etymology shows own lex class ([721987](https://code.itinerare.net/itinerare/Mundialis/commit/721987e590ab654d9d4206c920b83c095b5d7572))
* Issue w conj/decl autogen ([3eb780](https://code.itinerare.net/itinerare/Mundialis/commit/3eb7803e1ab5c2b3937de8897ab67fe97edea7f2))
* New entry parsed description not saved ([0ffdcc](https://code.itinerare.net/itinerare/Mundialis/commit/0ffdcc3e9a835530e6ec3c9c897d02ed0cd89fc0))
* Wiki link parse error on create entry ([8b9b03](https://code.itinerare.net/itinerare/Mundialis/commit/8b9b03ca5a58ecd35e1b5e2e4e42ec4b12a66399))

##### Pages

* Allow dashes in parsed links ([ec5b6f](https://code.itinerare.net/itinerare/Mundialis/commit/ec5b6f149d1bd79709196fd76d5c45e47858338e))
* Current page is in image edit pages ([496ee7](https://code.itinerare.net/itinerare/Mundialis/commit/496ee7aebdd3e73992a4ffdfb6a69a247cfdd0c7))
* Deleted page/image results in recent edits ([4c71f8](https://code.itinerare.net/itinerare/Mundialis/commit/4c71f88413de4637b7e1b0f8662bc37a79ffb5a2))
* Error creating pages ([de4c42](https://code.itinerare.net/itinerare/Mundialis/commit/de4c42a86f9ce6d58415441625830459cf8c3871))
* Error restoring page ([08bce5](https://code.itinerare.net/itinerare/Mundialis/commit/08bce56cacb89aef5fde51e6313a2b4778a88d05))
* Error viewing recent page/image widget ([b387a1](https://code.itinerare.net/itinerare/Mundialis/commit/b387a19d40a8ca0c7fbebc7cde367d62a62d7006))
* Handle link parse special chars better ([3bc7e2](https://code.itinerare.net/itinerare/Mundialis/commit/3bc7e24babac2513101e0e8624a511cbbbe15713))
* Image info formatting tweak ([543ee8](https://code.itinerare.net/itinerare/Mundialis/commit/543ee8136b44bc5b3a84dc22b351697aba681594))
* Images in info popup/page not centered ([9282ca](https://code.itinerare.net/itinerare/Mundialis/commit/9282cadad8fcb28e8ed5ac31c804ff04d7d6e272))
* Introduction crowds infobox ([8c6c3e](https://code.itinerare.net/itinerare/Mundialis/commit/8c6c3e1ad293de539fdb8fc116a3cebd90eab28b))
* Link parsing error ([142392](https://code.itinerare.net/itinerare/Mundialis/commit/14239233a77b1e1b0c1ae06c247d52b81d7d1438))
* Navbox displays deleted pages ([251163](https://code.itinerare.net/itinerare/Mundialis/commit/2511635fa9d858825a365025cf0ebe2433adf3f5))
* Navbox doesn't display subcat pages... ([8329fb](https://code.itinerare.net/itinerare/Mundialis/commit/8329fb11fe7f7c8dec1d72011c2b5701cc3f06df))
* Parsed links don't display in infobox ([a59195](https://code.itinerare.net/itinerare/Mundialis/commit/a59195ea51eff9c16ee3bded1ce04827faecb8eb))
* Random page errors when no pages ([a4056d](https://code.itinerare.net/itinerare/Mundialis/commit/a4056d5b13f4a63045dd5b51de33352d154a59ce))
* Routing error ([893f3a](https://code.itinerare.net/itinerare/Mundialis/commit/893f3a43f30f52ddb571ec9fb2ffe13f17e055af))
* Section length calc doesn't add right ([904a6a](https://code.itinerare.net/itinerare/Mundialis/commit/904a6a1bcce147ac7f100bc749bf68cc0af58cb1))
* Subject breadcrumbs links outdated ([c86294](https://code.itinerare.net/itinerare/Mundialis/commit/c862949f09b079afc0bb0be12a84a5704f332faa))

##### People

* Error viewing relationships ([db68ae](https://code.itinerare.net/itinerare/Mundialis/commit/db68ae25cc34e667163821c537edd23d9867ec89))

---

## [1.0.0-pre1](https://code.itinerare.net/itinerare/Mundialis/compare/8c3e2c6ef82213b81555484b5694b4ae87dba3c4...1.0.0-pre1) (2021-08-07)


### Features

* Add category summaries and images ([7398cb](https://code.itinerare.net/itinerare/Mundialis/commit/7398cbc7fd5da1c486ac1ae40f4481e5bd4d8afb))
* Add general update command ([52e4e2](https://code.itinerare.net/itinerare/Mundialis/commit/52e4e2e0e76c7a17cda9ae0bd0b7d3c3e2bad68e))
* Add infobox/section sorting to templates ([c9e188](https://code.itinerare.net/itinerare/Mundialis/commit/c9e188f3b66e2fda95f891b1aa2b3445baabf4c3))
* Add page watching/watched pages ([40df1b](https://code.itinerare.net/itinerare/Mundialis/commit/40df1b541c1060425bb6a65f3eae1e603b760781))
* Add setup command, for convenience ([888075](https://code.itinerare.net/itinerare/Mundialis/commit/888075a6876e786a90a8e15a0ce320ce52e7188c))
* Add site settings ([5145e2](https://code.itinerare.net/itinerare/Mundialis/commit/5145e26001167245f90b82e679287a91bf671da9))
* Add subject category creation and editing ([efa86a](https://code.itinerare.net/itinerare/Mundialis/commit/efa86a475ae5fdd18d444ab9f6caf42764447b22))
* Add subject links to navbar ([fe7958](https://code.itinerare.net/itinerare/Mundialis/commit/fe79580799e933f2383c388eae1ee9a761826a43))
* Add subject selection to template sections ([55dfad](https://code.itinerare.net/itinerare/Mundialis/commit/55dfad61cf3a6df20fb79424eb424b9855cc7b7c))
* Add widget selection to template builder ([bc0a17](https://code.itinerare.net/itinerare/Mundialis/commit/bc0a17f876e792aaefa7a18a58955fabdc956ace))
* Allow sorting new infobox/template sections ([a621fd](https://code.itinerare.net/itinerare/Mundialis/commit/a621fda905fd463dc84ebaf29e24038aec7c8880))
* Basic admin panel setup ([b83d4a](https://code.itinerare.net/itinerare/Mundialis/commit/b83d4a97a3a998596fc6d8e73794e5afff66d3a7))
* Basic auth setup and views ([725ed8](https://code.itinerare.net/itinerare/Mundialis/commit/725ed8b6f512717ed428237da00d9a6bad30c94f))
* Basic site page setup ([b5f8bf](https://code.itinerare.net/itinerare/Mundialis/commit/b5f8bf5cbf6b2263a1e3fcab51d4055b3fec09cb))
* Clean up widgets ([dfcd60](https://code.itinerare.net/itinerare/Mundialis/commit/dfcd6086a72295facecb73501b82b1f0b163da4d))
* Clearer instructions around template editing ([3fdd4e](https://code.itinerare.net/itinerare/Mundialis/commit/3fdd4e19d23e802fe14d8c45dba331d8d845fce1))
* Helper functions ([4073cf](https://code.itinerare.net/itinerare/Mundialis/commit/4073cf0ccab141d7353dee5938bd39b033db934e))
* Implement basic template editor for subjects ([3f7628](https://code.itinerare.net/itinerare/Mundialis/commit/3f76287486ab51ac45e8ac900e8072ed4eb4b04f))
* Implement cascading changes for templates ([aaf235](https://code.itinerare.net/itinerare/Mundialis/commit/aaf235471e01a1aac22f7e580a3450136462843d))
* Implement cascading for category templates ([1fd9a7](https://code.itinerare.net/itinerare/Mundialis/commit/1fd9a7479e45b02b44158cff70a377fbde108f8b))
* Implement category sorting ([6740c4](https://code.itinerare.net/itinerare/Mundialis/commit/6740c4a64c9f6279c28d6a075b6469df237fad2e))
* Implement more basic perms checks and route setup ([d64c25](https://code.itinerare.net/itinerare/Mundialis/commit/d64c25474f64efc566649ceb57db5ed4ba0106b6))
* Implement rank editing ([90d9f9](https://code.itinerare.net/itinerare/Mundialis/commit/90d9f9357156e9b9ce190ab3751afdda650509be))
* Navbar, dashboard updates ([356e5e](https://code.itinerare.net/itinerare/Mundialis/commit/356e5e20c81b346ae8ba44fa88689566a5f4eb2c))
* Replace placeholder images with solid colour ([0a15d3](https://code.itinerare.net/itinerare/Mundialis/commit/0a15d30d15e60b992c7d8e18e30ce392af6018a9))
* Start of admin template panels/routes ([65a32f](https://code.itinerare.net/itinerare/Mundialis/commit/65a32f6202e4ea2416bf3f74b057e73f96fd8fe5))
* Update subjects config, rename subject view ([6dd20b](https://code.itinerare.net/itinerare/Mundialis/commit/6dd20b8ced15cdbfba7acb18c100ad58a55556cb))

##### Auth

* Add admin user edits, email functions ([7aae88](https://code.itinerare.net/itinerare/Mundialis/commit/7aae885325cf7d7405a92903006de45afc8e764b))
* Add page delete notification ([7d3241](https://code.itinerare.net/itinerare/Mundialis/commit/7d32411ae567bb56538fb8b12169475fd27f93da))
* Implement user profiles ([3f2eb0](https://code.itinerare.net/itinerare/Mundialis/commit/3f2eb0e357b2725949838b6c1f8b3c8d861c4b2f))
* Implement user settings ([092fb6](https://code.itinerare.net/itinerare/Mundialis/commit/092fb65ca06efe2f888dde2ccf4e7ac414dc34f6))
* Invitation code system ([b052d3](https://code.itinerare.net/itinerare/Mundialis/commit/b052d30edd10267d28acc2d222c4f38a693e1d0f))
* Notification system ([3eeaab](https://code.itinerare.net/itinerare/Mundialis/commit/3eeaabeb8b0adba792dd6f7e658b6e4072bd9447))

##### Lang

* Add category settings population ([e208b5](https://code.itinerare.net/itinerare/Mundialis/commit/e208b51d67e661d9520f5062f027aab427cb766e))
* Add etymology and display to entries ([5fa464](https://code.itinerare.net/itinerare/Mundialis/commit/5fa46477615d81eeb51b281429d22cd0d68e22a2))
* Add lexicon settings and categories ([f4c806](https://code.itinerare.net/itinerare/Mundialis/commit/f4c806456678ac739a04a89e2f236372b2f7a46b))
* Add page linking in definitions ([4123ee](https://code.itinerare.net/itinerare/Mundialis/commit/4123eef5a562f48cf8cdc8a28e7f1023191b6c98))
* Add recursive entry descendant listing ([d3ca77](https://code.itinerare.net/itinerare/Mundialis/commit/d3ca775f397525675d53e4b4112b3e9e2f4e8a74))
* Add sort by meaning to entry index ([ac6b05](https://code.itinerare.net/itinerare/Mundialis/commit/ac6b053b676f0eafd3b5cf7043fe858800a4f310))
* Easier editing of auto-conj rules ([1bedd5](https://code.itinerare.net/itinerare/Mundialis/commit/1bedd5dc1d531d6187996160ad42b4f19073873c))
* Entry conjugation/declension, display ([0c25cb](https://code.itinerare.net/itinerare/Mundialis/commit/0c25cbb3548007406b8fc4cfa5380832c23f0003))
* Finish adding category delete and sort ([f191f9](https://code.itinerare.net/itinerare/Mundialis/commit/f191f920444cb3765e6b8571079bd491866dff73))
* Lexicon entry display and creation ([fb783c](https://code.itinerare.net/itinerare/Mundialis/commit/fb783cdb71e6a0f6ee9b55bd3d3d291a97d64e57))
* Make conj/decl collapsible ([be4243](https://code.itinerare.net/itinerare/Mundialis/commit/be4243c4c2e02ffafa72f45142717c1889cdf8d7))
* Sort entry descendants ([7c45eb](https://code.itinerare.net/itinerare/Mundialis/commit/7c45eb3fc0dea53f287e6e451de07b6b6765b2a7))

##### Pages

* Add ability to filter by tag(s) ([9dc371](https://code.itinerare.net/itinerare/Mundialis/commit/9dc371cb2592ae5b36821a33720bf34bd4967c22))
* Add advanced tags + navboxes ([ddb8c9](https://code.itinerare.net/itinerare/Mundialis/commit/ddb8c905f20333dcdb2847beed18ca10d39016e9))
* Add auth-dependent visibility ([7fe483](https://code.itinerare.net/itinerare/Mundialis/commit/7fe4837abaaeaf46ca6b13a4176b8b7b9e1f4a5f))
* Add basic page creation and editing ([6befee](https://code.itinerare.net/itinerare/Mundialis/commit/6befeea7cde6a6d4a39a16ab1241dfc28f57792e))
* Add basic page display ([94850e](https://code.itinerare.net/itinerare/Mundialis/commit/94850e288bd450eb07395efc9997ed7749ae0841))
* Add basic search/sort to page indexes ([6443d0](https://code.itinerare.net/itinerare/Mundialis/commit/6443d081516fc34eef5674dd33ac4e370fe0930e))
* Add disambiguation features ([732140](https://code.itinerare.net/itinerare/Mundialis/commit/7321408cdb280d281df2fad79b9ee1b366b7952e))
* Add list of recent page/image changes ([27a853](https://code.itinerare.net/itinerare/Mundialis/commit/27a853328017ca43005a788d75a4edbc5e5ab8f3))
* Add optgroups to image page selection ([4686b7](https://code.itinerare.net/itinerare/Mundialis/commit/4686b76684af34064cf55c3cc45bb4f4bb2f7439))
* Add page image system ([f68807](https://code.itinerare.net/itinerare/Mundialis/commit/f688076b7607e08bb2b4b2ae87763eff6f810d9b))
* Add page protection ([e5de77](https://code.itinerare.net/itinerare/Mundialis/commit/e5de77c272be4ee28112303033804429273e29d6))
* Add protected pages special page ([9bfe95](https://code.itinerare.net/itinerare/Mundialis/commit/9bfe95d3c077c907a817e96f92bd871e6c8328e2))
* Add reason and minor edit to versions ([82cad6](https://code.itinerare.net/itinerare/Mundialis/commit/82cad6c0e0bbf1264ff3dca1b9e7e02a452451c1))
* Add revision history, image history ([c736b2](https://code.itinerare.net/itinerare/Mundialis/commit/c736b2e63ef67f0961b27de8548007d19d1821b7))
* Add unwatched special page ([cc98cd](https://code.itinerare.net/itinerare/Mundialis/commit/cc98cdb18db9979d9ce9ca86aeb971ce5416008c))
* Add user list special page ([9f0239](https://code.itinerare.net/itinerare/Mundialis/commit/9f023950c26d00340e4364a85ea36a17bf4165bf))
* Add visibility display to page header ([1daea5](https://code.itinerare.net/itinerare/Mundialis/commit/1daea5b0751f9c5f37240c7d088e9e70d2aa9379))
* Add wanted pages, what links here ([fe8ebc](https://code.itinerare.net/itinerare/Mundialis/commit/fe8ebc9d5617dad16ffd81cf8b7739ed9afaf7cb))
* Add wiki-style link parsing ([38bd23](https://code.itinerare.net/itinerare/Mundialis/commit/38bd2352d25506eca33aba4bebd9cdcdcd6a365c))
* All pages special page ([2fcc70](https://code.itinerare.net/itinerare/Mundialis/commit/2fcc7015eace2a86afc2efc99e88007095926ad9))
* All tags list, all images list ([abcbe6](https://code.itinerare.net/itinerare/Mundialis/commit/abcbe60ac318cc0cde593bfcdc8651e6d9c2b0f5))
* Auto table of contents ([28ee51](https://code.itinerare.net/itinerare/Mundialis/commit/28ee517ca867fb4e46d828a5f32df63d690e0b22))
* Better org of category/page indices ([950f9b](https://code.itinerare.net/itinerare/Mundialis/commit/950f9b109a1e6bce9bcfd6abd44ddc7ccf549703))
* Flow for creating wanted pages ([d4fdc1](https://code.itinerare.net/itinerare/Mundialis/commit/d4fdc1c0b0ca85d2ce9f93772a0a53e0585c9e20))
* Implement page tags ([457b1e](https://code.itinerare.net/itinerare/Mundialis/commit/457b1e54b1eab6b6c656a33241915838c70c2128))
* Implement specialized subject fields ([94a7d9](https://code.itinerare.net/itinerare/Mundialis/commit/94a7d92f91f324ed019829a744b25da6267e57bc))
* Implement updates on wanted page creation ([0a230a](https://code.itinerare.net/itinerare/Mundialis/commit/0a230a87ae696837a0f026828f46b60e6ce473ed))
* Implement utility tags ([d99eee](https://code.itinerare.net/itinerare/Mundialis/commit/d99eeeefc3b9fedce9a8f299c1313d0e46c40288))
* Make sections collapseable ([548ab1](https://code.itinerare.net/itinerare/Mundialis/commit/548ab1fcb407f05b1369ec026a2b8fa4f19cc321))
* More precise people age calculation ([5aede2](https://code.itinerare.net/itinerare/Mundialis/commit/5aede2b8e3716c1c42119ff7170d95406f64f6e8))
* Most/least revised pages list ([994c96](https://code.itinerare.net/itinerare/Mundialis/commit/994c96afec9c74e06b1059b4a7c812113f724ac0))
* Most linked-to pages special page ([d5fa0c](https://code.itinerare.net/itinerare/Mundialis/commit/d5fa0cb5e7071b75c933344c790bbb0fba042fd5))
* Most tagged/untagged special pages ([f34a93](https://code.itinerare.net/itinerare/Mundialis/commit/f34a93a764e97315b68d3139f38b7423e06a7d8e))
* Page links display summary on hover ([66b003](https://code.itinerare.net/itinerare/Mundialis/commit/66b003439734d33c2a09cf18d4bd13c460732e30))
* Page moving ([9ccfe7](https://code.itinerare.net/itinerare/Mundialis/commit/9ccfe745fd0c2ce945807e28be2c32a93861b652))
* Subject and category index views ([5fc5e0](https://code.itinerare.net/itinerare/Mundialis/commit/5fc5e0ee838578fcf63e9c2de921f6de8676459a))

##### People

* Add basic relationship tracking ([ee2c63](https://code.itinerare.net/itinerare/Mundialis/commit/ee2c6381baa7319ab3f170c3a09c91372ac9cae5))
* Adjust ancestry styling ([20f141](https://code.itinerare.net/itinerare/Mundialis/commit/20f1412490c1b50a35c061be8f8508cf8e79cb40))
* Basic family tree/listing page ([eb0ab9](https://code.itinerare.net/itinerare/Mundialis/commit/eb0ab97b2b4865c5cd2eb2ed46f5b3b2f0d455db))

##### Resources

* Add a bunch of basic resources ([bd2eeb](https://code.itinerare.net/itinerare/Mundialis/commit/bd2eeb2f8dfd4e11377aa9cb4f38d58a97e5fea2))
* Add command to generate changelog ([b4e2b8](https://code.itinerare.net/itinerare/Mundialis/commit/b4e2b80b22bb269965bd4ceb620d27b5658ea821))
* Add lightbox UI images ([017ee3](https://code.itinerare.net/itinerare/Mundialis/commit/017ee3ba1fc5405d0a3652d627a8b9fed72c49d5))
* Add settings config ([9ee9d3](https://code.itinerare.net/itinerare/Mundialis/commit/9ee9d324d4bb690c4a8f8bc6e7a64803f184986f))
* Track composer.lock ([c79b77](https://code.itinerare.net/itinerare/Mundialis/commit/c79b778ba108d78f051ca8aa99c006b911fc987e))

##### Time

* Add timeline display ([619fa5](https://code.itinerare.net/itinerare/Mundialis/commit/619fa57e724532e6aae7028143c94c16489f9cb1))
* Add 'use for dates' check to time divisions ([eda3bd](https://code.itinerare.net/itinerare/Mundialis/commit/eda3bd29f236e451731228a0f4804a97c8ee9319))
* Chronologies display ([9d54d5](https://code.itinerare.net/itinerare/Mundialis/commit/9d54d50fdc5b595953e81402dee78f9c92a71c51))
* Implement chronology & time divisions ([fd5caf](https://code.itinerare.net/itinerare/Mundialis/commit/fd5cafa33061912d800112754aa3f4c331df8b52))

### Bug Fixes

* Admin user routes clobber rank/invite ones ([eed999](https://code.itinerare.net/itinerare/Mundialis/commit/eed999f329872cdfa1d25ba638c43f75628e9754))
* Cascading field/widget template changes error ([0e7337](https://code.itinerare.net/itinerare/Mundialis/commit/0e73377344da24abc3b1fb58cc39b050eaddb16c))
* Concepts typo ([b7d998](https://code.itinerare.net/itinerare/Mundialis/commit/b7d99828d150dc147f432c94464fbe4e0d2452ef))
* Error editing subject categories ([b35834](https://code.itinerare.net/itinerare/Mundialis/commit/b3583453a9e39db22956e208bdf575298392e07d))
* Issue detecting field changes as removals ([47abc8](https://code.itinerare.net/itinerare/Mundialis/commit/47abc871203cb90ac8f7bc2c2d0ead3fef0c0cc4))
* Logo present in mobile sidebar ([a199cc](https://code.itinerare.net/itinerare/Mundialis/commit/a199cc6ed5e391eed9aaefdcd5312c0d0d292119))
* Minor template builder issues ([7fe693](https://code.itinerare.net/itinerare/Mundialis/commit/7fe6937a102e704ba3c6bbd19f05116637497de1))
* Removing template section removes all of them ([78ad4a](https://code.itinerare.net/itinerare/Mundialis/commit/78ad4a6110e8db9e9d410a4d47ca97acc0da814f))
* Setup-admin reset out of order ([485b2a](https://code.itinerare.net/itinerare/Mundialis/commit/485b2af675edb0a2bb406a26494cad51cadca39f))
* TinyMCE won't initialize ([c2370a](https://code.itinerare.net/itinerare/Mundialis/commit/c2370aa4fb0ef2538f3493f5ae35440509eb2a87))
* Typo in subjects config file ([0d7fa2](https://code.itinerare.net/itinerare/Mundialis/commit/0d7fa2527a25716155025ac055fef64dbf88434b))
* Update command not declared properly ([90beda](https://code.itinerare.net/itinerare/Mundialis/commit/90beda5868c93664505cee6106ac780e9c8bca54))
* Validation errors do not appear ([30330c](https://code.itinerare.net/itinerare/Mundialis/commit/30330c4946afc24c0a0319f0fba542bc981bed3c))

##### Lang

* Add missing auth check to entry index ([d2baa2](https://code.itinerare.net/itinerare/Mundialis/commit/d2baa277cd67c8b25d88e1779b0902e6306c4e4e))
* Can't edit categories w/o properties ([7f09cc](https://code.itinerare.net/itinerare/Mundialis/commit/7f09ccbe2af4dc292fe9a8b02849850274dc4506))
* Category conj/declen order inconsistent ([3279f1](https://code.itinerare.net/itinerare/Mundialis/commit/3279f156d95ddc0b34e7786a5729efce6f868196))
* Category deletion uses wrong function ([0e2583](https://code.itinerare.net/itinerare/Mundialis/commit/0e2583658f74d1da932d4523f203ae83a8df772d))
* Category edit button links to wrong page ([b3ecc6](https://code.itinerare.net/itinerare/Mundialis/commit/b3ecc6fabffa0ad4af4ac19f75b856a7ff7651b5))
* Category table display wonky ([603464](https://code.itinerare.net/itinerare/Mundialis/commit/603464d222f2306035bebbff4c500beec7012f7d))
* Creation of empty etymology records ([aa6e42](https://code.itinerare.net/itinerare/Mundialis/commit/aa6e42f8dcddbbad9f22af381229f7edd667ae31))

##### Pages

* Auto-ToC width wonky on mobile ([c629f8](https://code.itinerare.net/itinerare/Mundialis/commit/c629f88362d61173ca7fd70a83c46aef402217bc))
* Cannot remove extra links from images ([7d031e](https://code.itinerare.net/itinerare/Mundialis/commit/7d031e747232b1435855c1f83e67bf9549a15f23))
* Cannot set page non-visible ([578712](https://code.itinerare.net/itinerare/Mundialis/commit/57871216fc47757ae8da22fc382ed2b5302909f0))
* Category optgroups sorted wrong ([35e1ed](https://code.itinerare.net/itinerare/Mundialis/commit/35e1ed905c03b7e532831f2c537db2786d56bd63))
* Check image has pages when restoring ([c91f53](https://code.itinerare.net/itinerare/Mundialis/commit/c91f53632a8245fd0052813b3e306784bf17cb35))
* Data column not nullable ([b7b0ed](https://code.itinerare.net/itinerare/Mundialis/commit/b7b0ed9b4159cc1f4caa711a8fb0cb781390219e))
* Edit/reset allow deleted page title ([aec9de](https://code.itinerare.net/itinerare/Mundialis/commit/aec9de61db75f8138e66349f9e2269ab608324c4))
* Error fetching page data from version ([3e6ccb](https://code.itinerare.net/itinerare/Mundialis/commit/3e6ccb26d0f3517797f04a45609dbebe36f8ff85))
* Error viewing categories ([878c97](https://code.itinerare.net/itinerare/Mundialis/commit/878c97f681eac8d140dd0d9a330655b673c16991))
* Force-deleted pages error changes list ([98f958](https://code.itinerare.net/itinerare/Mundialis/commit/98f95886d6b1a3e2f59c28fa8a3ea759dced6279))
* Gallery formatting slightly wonky ([1153b4](https://code.itinerare.net/itinerare/Mundialis/commit/1153b4c9ef608a94e8fb5c07e03301eff5a16987))
* Hidden pages visible in navbox ([52b7db](https://code.itinerare.net/itinerare/Mundialis/commit/52b7db8580b1f3bdb413732a83640edfbd206634))
* Image files aren't deleted properly ([f7380d](https://code.itinerare.net/itinerare/Mundialis/commit/f7380d6aa6be16884f54ea6a001a9da6ebc89fc5))
* Image revision log takes up more space ([e45865](https://code.itinerare.net/itinerare/Mundialis/commit/e458651c373beee3412602f8448f37799d75157e))
* Image unlink deletes page not detaches ([d3ae71](https://code.itinerare.net/itinerare/Mundialis/commit/d3ae71900e32311387ad8d81c34f13f8ba73feab))
* Link parse detection issue, dupe links ([72c475](https://code.itinerare.net/itinerare/Mundialis/commit/72c4751a8d1118ce4c272acd7484494d1f45b1d1))
* Link parse issues for people, disambig ([ab7d77](https://code.itinerare.net/itinerare/Mundialis/commit/ab7d77b2275c79573bd3eab1833a84c816160ac3))
* More consistent img creator processing ([4afabc](https://code.itinerare.net/itinerare/Mundialis/commit/4afabc8b80c72de39d1b7a30fba3957ed8cc6ba9))
* Navbox errors when no context pages ([486c47](https://code.itinerare.net/itinerare/Mundialis/commit/486c4718f01da90c02cb114892c21b3613a112ff))
* Page index top pagination out of order ([9cf99b](https://code.itinerare.net/itinerare/Mundialis/commit/9cf99b96d51a620482199b5e92079f909c2d9e31))
* Page link detection broken ([2f1e45](https://code.itinerare.net/itinerare/Mundialis/commit/2f1e45a8c777dd526be7bda3521fdb8c99080957))
* Page list cols try to nest ([a9d334](https://code.itinerare.net/itinerare/Mundialis/commit/a9d3344243cbd84e44ec738fb33b8ec2274d62c2))
* Parent place field populated wrong ([511604](https://code.itinerare.net/itinerare/Mundialis/commit/51160477defeea2b3c8d6c79396ad600e275aeec))
* Subject query inexplicably hardcoded ([b8caa1](https://code.itinerare.net/itinerare/Mundialis/commit/b8caa1cf938e7278c69909ecd93b6903fcd5ecb1))
* Subject routes not adjusted ([835e57](https://code.itinerare.net/itinerare/Mundialis/commit/835e57f39782871006f1a5baa21aae9ea8dd48f8))
* Tag field not labeled optional ([d0b508](https://code.itinerare.net/itinerare/Mundialis/commit/d0b5087ff68673a99ea8c58e1af01be3196122b7))
* Version data displaying incorrectly ([532103](https://code.itinerare.net/itinerare/Mundialis/commit/532103cccbbb3c807d96c7ed602ceb0e4da193db))
* Wiki link parsing backwards ([751804](https://code.itinerare.net/itinerare/Mundialis/commit/751804f6670f913265f368018595c0a0d446412f))

##### People

* Incorrect form order when creating relationships ([6d1b48](https://code.itinerare.net/itinerare/Mundialis/commit/6d1b48a3775078ebd950e7975b0753b272d3512b))

##### Resources

* Changelog command has wrong description ([ff1859](https://code.itinerare.net/itinerare/Mundialis/commit/ff1859528074ce1d44be37ea75e3ea725890a803))

##### Time

* Chronology editing points at category editing ([20b89f](https://code.itinerare.net/itinerare/Mundialis/commit/20b89f4f954b3bdc27cdfe75f3c8841b15dcb1a0))
* Chronology index table displays wonky ([086068](https://code.itinerare.net/itinerare/Mundialis/commit/086068a5aada957adb6078f1eb9c485339ddd6f0))
* Error sorting chronologies ([541150](https://code.itinerare.net/itinerare/Mundialis/commit/54115014b2f62220236bb463e28fe9455906d04d))
* Fix error editing divisions ([68ef60](https://code.itinerare.net/itinerare/Mundialis/commit/68ef605bab10629347d4778ff32e8f1d437e0507))
* Renaming time divisions would delete/recreate ([98760d](https://code.itinerare.net/itinerare/Mundialis/commit/98760d898ea0f25d38dbf58c6aa3903b4623e85a))

---

