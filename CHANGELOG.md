<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [2.2.5](https://github.com/itinerare/Mundialis/compare/v2.2.4...v2.2.5) (2024-01-14)

### Bug Fixes


##### Users

* Improve password reset flow ([c04a07](https://github.com/itinerare/Mundialis/commit/c04a0794ac44334fabb833e52a2ff4be978992ae))


---

## [2.2.4](https://github.com/itinerare/Mundialis/compare/v2.2.3...v2.2.4) (2023-08-20)

### Bug Fixes

* Admin subject category table format/header ([93b561](https://github.com/itinerare/Mundialis/commit/93b561f1fa15df3c01ba689270788a8a9e363644))

##### Time

* Fix viewing chronology with children ([96d207](https://github.com/itinerare/Mundialis/commit/96d207bfeacf5a19a90cc153e3af5d0437d8da9f))

##### Users

* Improve avatar handling ([4e63a0](https://github.com/itinerare/Mundialis/commit/4e63a055a15631573f52e629d1a9b11e3d5b0c6a))


---

## [2.2.3](https://github.com/itinerare/Mundialis/compare/v2.2.2...v2.2.3) (2023-08-13)

### Bug Fixes


##### Lang

* Fix uncategorized entry displayName link ([e8646e](https://github.com/itinerare/Mundialis/commit/e8646e5292a287d5d19bb17ca22e83b26f317758))


---

## [2.2.2](https://github.com/itinerare/Mundialis/compare/v2.2.1...v2.2.2) (2023-08-06)

### Bug Fixes


##### Lang

* Fix descendent link for a non-categorized entry ([2ef9f0](https://github.com/itinerare/Mundialis/commit/2ef9f0858a84d21ccd054d860594993d7ab8a982))

##### Pages

* Add show/hide text swap JS to version view ([9c5c5a](https://github.com/itinerare/Mundialis/commit/9c5c5abf5e03a173b6ee09e6770a8f26cbbed1a6))

##### Users

* More refined deleted user check on invitation page ([3420dd](https://github.com/itinerare/Mundialis/commit/3420ddba96a47e5b15d1579375f132f96b21b54c))


---

## [2.2.1](https://github.com/itinerare/Mundialis/compare/v2.2.0...v2.2.1) (2023-07-30)

### Bug Fixes


##### Users

* Add extra check for recipient on invitation code page ([aad6f6](https://github.com/itinerare/Mundialis/commit/aad6f6972f69b1ad754a65ef7498c77e28454b84))


---

## [2.2.0](https://github.com/itinerare/Mundialis/compare/v2.1.3...v2.2.0) (2023-07-23)

### Features

* Set up mix, update bootstrap ([480b87](https://github.com/itinerare/Mundialis/commit/480b875da2cfa0fcab66a917a43a1de1da1c9662))


---

## [2.1.3](https://github.com/itinerare/Mundialis/compare/v2.1.2...v2.1.3) (2023-07-16)

### Bug Fixes

* Fix adding/editing help tooltips for infobox fields ([d1d527](https://github.com/itinerare/Mundialis/commit/d1d527f2994832db4043c92826e50e2c6e814826))
* General PHP clean-up to help static analysis ([c8df23](https://github.com/itinerare/Mundialis/commit/c8df23030f5bda5c277b8e4337a2478266a5b82b))
* Prevent basic page field key overlap ([a14db1](https://github.com/itinerare/Mundialis/commit/a14db1a834072c2b1d6c2d925892b53c33b992ef))

##### Pages

* Route to pages with slugs beginning with numbers; closes #374 ([98f325](https://github.com/itinerare/Mundialis/commit/98f325c2bc53364225db2be9b3a70b3476e26ac8))

##### Time

* Resolve some timeline formatting issues ([396d83](https://github.com/itinerare/Mundialis/commit/396d836e0cf614442e53bbaed9a72451fe527316))


---

## [2.1.2](https://github.com/itinerare/Mundialis/compare/v2.1.1...v2.1.2) (2023-05-07)

### Bug Fixes


##### Pages

* Allow all utility tags to be removed from a page ([3bb16c](https://github.com/itinerare/Mundialis/commit/3bb16cfbd790aaea7538e327729b6a5685bf8aed))
* Allow special and accented characters to be correctly parsed by wiki link syntax ([35d5d9](https://github.com/itinerare/Mundialis/commit/35d5d915d3156539a63f4b9176fef173ea31d52f))


---

## [2.1.1](https://github.com/itinerare/Mundialis/compare/v2.1.0...v2.1.1) (2023-04-23)

### Bug Fixes


##### Pages

* Always show image creator fields if no creators are set ([ee3ae4](https://github.com/itinerare/Mundialis/commit/ee3ae4d4d7d507bee71cc4fa79c2776d6b39324f))
* Properly require one of image creator ID/URL ([006d94](https://github.com/itinerare/Mundialis/commit/006d946439207d0979ac75d46e02acbef56cd3a8))

##### Tests

* Fix sent data in edit image creator with user test ([a5ee9b](https://github.com/itinerare/Mundialis/commit/a5ee9b85ca7551bf6a21cbd90aabeecc39aa510c))


---

## [2.1.0](https://github.com/itinerare/Mundialis/compare/v2.0.0...v2.1.0) (2022-07-24)

### Features

* Add code coverage test to composer scripts ([643c8b](https://github.com/itinerare/Mundialis/commit/643c8b0fb40bbbecff66b64e1358067388c92cc9))


---

## [2.0.0](https://github.com/itinerare/Mundialis/compare/v1.3.5...v2.0.0) (2022-05-15)
### ⚠ BREAKING CHANGES

* Update to Laravel 9 ([60f70d](https://github.com/itinerare/Mundialis/commit/60f70dc0bc8dfa1db708e0539e687749766e33a2))
* Update to PHP 8 ([e1947c](https://github.com/itinerare/Mundialis/commit/e1947c4629a923d718da4b59f72eae491e5d78cd))


---

## [1.3.3](https://github.com/itinerare/Mundialis/compare/v1.3.2...v1.3.3) (2022-02-06)
### Bug Fixes

* Headers already sent error; fixes #70 ([b37b63](https://github.com/itinerare/Mundialis/commit/b37b630b2a387f43804909936c6a1db5e09c415a))


---

## [1.3.2](https://github.com/itinerare/Mundialis/compare/v1.3.1...v1.3.2) (2022-01-31)
### Bug Fixes


##### License

* Update info around patron license ([3fac0b](https://github.com/itinerare/Mundialis/commit/3fac0b90d5759c65894518ec7903ef74a7641cec))


---

## [1.3.1](https://github.com/itinerare/Mundialis/compare/v1.3.0...v1.3.1) (2022-01-30)
### Bug Fixes


##### Time

* Clarify division page verbiage ([c1c2f6](https://github.com/itinerare/Mundialis/commit/c1c2f6a5e4a597037ec746e94902374c0b3ea450))
* Compatibility measures for old dates ([0572c6](https://github.com/itinerare/Mundialis/commit/0572c65b2d74689c9a685b05ce30c268f1cec311))
* Date fields not keyed by ID ([14a72f](https://github.com/itinerare/Mundialis/commit/14a72fba1e62749113bb71faf1c180ae7a25b046))
* Error formatting timeline ([a398b4](https://github.com/itinerare/Mundialis/commit/a398b47fcfbcdbc460e6a33716fdf9bc23eeea2b))
* Error viewing date with deleted divisions; fixes #55 ([412845](https://github.com/itinerare/Mundialis/commit/412845cc25a6bc76809b55aa1924bd0a0e5a1e17))


---

## [1.3.0](https://github.com/itinerare/Mundialis/compare/v1.2.0...v1.3.0) (2022-01-16)
### Features


##### Lang

* Switch lexicon settings to input groups; closes #26 ([0e3720](https://github.com/itinerare/Mundialis/commit/0e37208604b64cbcd2a85c5d1f4e09654cc86654))

##### Tests

* Add subject-specific page view tests; closes #45 ([47797d](https://github.com/itinerare/Mundialis/commit/47797dde1e90e03c8886da611caa7e21a15dd384))

##### Time

* Switch divisions to input groups ([dc16b1](https://github.com/itinerare/Mundialis/commit/dc16b16bf6b7f29f5f03b78b6c09d64166fa5014))

### Bug Fixes

* Editing subject category unsets has_image ([5fafe2](https://github.com/itinerare/Mundialis/commit/5fafe2183795787928e3aadf89e79dcdef6d6ac6))

##### Lang

* Error removing all lexicon settings ([12c7fb](https://github.com/itinerare/Mundialis/commit/12c7fb356797d11a531ed05e2c25529fc7444711))

##### Pages

* Change section show/hide w/ state; fixes #29 ([6fcbaf](https://github.com/itinerare/Mundialis/commit/6fcbaf93bca72ca12911ab35a0d83c7e371b5fa2))
* Error falling back to subject template ([67d63f](https://github.com/itinerare/Mundialis/commit/67d63f3d9dece1bdc565d07d6b7d4703808171f3))
* Template fetch error w nested categories ([bdf0c1](https://github.com/itinerare/Mundialis/commit/bdf0c125c7dd31ca28da5780d82a473d3bee9812))

##### People

* Error displaying birth/death w/o date ([cde3cb](https://github.com/itinerare/Mundialis/commit/cde3cb6f3b847d2bc01c8d31d7054ccd31e159a7))

##### Tests

* Actually create page vers in view test ([98a338](https://github.com/itinerare/Mundialis/commit/98a33825d8fdb22ff53f6d803a71f0717d83d062))

##### Time

* Better fix for removing all divisions ([f00479](https://github.com/itinerare/Mundialis/commit/f004791086a90e31a63d2a668b9fdd9b0a8f09df))
* Error removing all time divisions ([75a2ac](https://github.com/itinerare/Mundialis/commit/75a2aca440434af79488543e254a4e8c7087d2e2))


---

## [1.2.0](https://github.com/itinerare/Mundialis/compare/v1.1.1...v1.2.0) (2022-01-09)
### Features


##### Tests

* Add template field tests; closes #25 ([cd0272](https://github.com/itinerare/Mundialis/commit/cd027294e14220e1620a5f3800baf7a1f22c86bf))
* Extend template field tests ([312183](https://github.com/itinerare/Mundialis/commit/3121830ed3ee2588c32f378888e3fdadb6e88aaf))
* Infobox page edit field tests ([f75ad9](https://github.com/itinerare/Mundialis/commit/f75ad9c8524bd804e0abd3c6925ac75497f57c2c))
* Page body edit field tests ([241ee1](https://github.com/itinerare/Mundialis/commit/241ee1f1e8c621d58695e4d03699cfd24214e85b))
* Page view field tests ([d3efb7](https://github.com/itinerare/Mundialis/commit/d3efb75049e57cf60dc9a5716f266bead16e520d))

### Bug Fixes

* Error editing infobox field default value ([e4b61e](https://github.com/itinerare/Mundialis/commit/e4b61ea033cd6a7ebe9e7acca0d27fc4113687bb))
* Make default value/choices exclusive for fields ([b769b5](https://github.com/itinerare/Mundialis/commit/b769b57c7c7fa7f9e11db0c9848a2c6361f4f3c0))
* Template builder entry verbiage ([2186bb](https://github.com/itinerare/Mundialis/commit/2186bbc3b60097d7508430931fc5e7b79fc5e136))

##### Pages

* Choice/mult display error in infobox ([563fa4](https://github.com/itinerare/Mundialis/commit/563fa4207f236fada18e87ef86c61f334304af73))
* Fix using default value ([b9e22c](https://github.com/itinerare/Mundialis/commit/b9e22ce26ec11acb0d23fa8b34595b529eca866d))
* Radio buttons don't re-fill properly ([77660d](https://github.com/itinerare/Mundialis/commit/77660d63c6c32ce037efcec6bf513da5107d9b10))
* View error with certain field types ([dd5831](https://github.com/itinerare/Mundialis/commit/dd5831e40ddecb84eac9cba8be7e62aa0d055ee2))

##### Tests

* Test class name issue ([4f4cfe](https://github.com/itinerare/Mundialis/commit/4f4cfe733a75ca2f9bd0e84112a1326ca394e98f))


---

## [1.1.1](https://github.com/itinerare/Mundialis/compare/1.1.0...v1.1.1) (2021-12-05)
### Bug Fixes


##### Backups

* Config cleanup; fixes #33 ([82679d](https://github.com/itinerare/Mundialis/commit/82679d60b991fba4607af5ca16f8ffd3f08490e7))

---

## [1.1.0](https://github.com/itinerare/Mundialis/compare/1.0.0...1.1.0) (2021-11-28)
### Features


##### Backups

* Set up backups ([20cffd](https://github.com/itinerare/Mundialis/commit/20cffd3d9acad1c3aba99c02a6ab92efe3d021f8))

### Bug Fixes

* Cannot create choose (x) infobox fields; fixes #24 ([866dc1](https://github.com/itinerare/Mundialis/commit/866dc102bcccecec7dffbc3f60b7def2cbda395d))
* Existing infobox field type JS issue ([2a49d8](https://github.com/itinerare/Mundialis/commit/2a49d870ada7d2213544b0d8faceaf5f1aa69e1c))

##### Pages

* Error viewing pages after adding field ([7f62c4](https://github.com/itinerare/Mundialis/commit/7f62c488073b31e0491f020922c4350822d8aa03))
* Multiple choice errors; fixes #28 ([04d143](https://github.com/itinerare/Mundialis/commit/04d14371e84e5cce2c9fe85de4fdfd6f3b2bb09c))

---

## [1.0.0](https://github.com/itinerare/Mundialis/compare/1.0.0-rc1...1.0.0) (2021-11-23)
### Features

* Add category summaries and images ([7398cb](https://github.com/itinerare/Mundialis/commit/7398cbc7fd5da1c486ac1ae40f4481e5bd4d8afb))
* Add further .env files to ignore ([532f94](https://github.com/itinerare/Mundialis/commit/532f9445788430018e139fa6673e007e2e0d6010))
* Add general update command ([52e4e2](https://github.com/itinerare/Mundialis/commit/52e4e2e0e76c7a17cda9ae0bd0b7d3c3e2bad68e))
* Add infobox/section sorting to templates ([c9e188](https://github.com/itinerare/Mundialis/commit/c9e188f3b66e2fda95f891b1aa2b3445baabf4c3))
* Add page watching/watched pages ([40df1b](https://github.com/itinerare/Mundialis/commit/40df1b541c1060425bb6a65f3eae1e603b760781))
* Add setup command, for convenience ([888075](https://github.com/itinerare/Mundialis/commit/888075a6876e786a90a8e15a0ce320ce52e7188c))
* Add site settings ([5145e2](https://github.com/itinerare/Mundialis/commit/5145e26001167245f90b82e679287a91bf671da9))
* Add subject category creation and editing ([efa86a](https://github.com/itinerare/Mundialis/commit/efa86a475ae5fdd18d444ab9f6caf42764447b22))
* Add subject links to navbar ([fe7958](https://github.com/itinerare/Mundialis/commit/fe79580799e933f2383c388eae1ee9a761826a43))
* Add subject selection to template sections ([55dfad](https://github.com/itinerare/Mundialis/commit/55dfad61cf3a6df20fb79424eb424b9855cc7b7c))
* Add widget selection to template builder ([bc0a17](https://github.com/itinerare/Mundialis/commit/bc0a17f876e792aaefa7a18a58955fabdc956ace))
* Allow sorting new infobox/template sections ([a621fd](https://github.com/itinerare/Mundialis/commit/a621fda905fd463dc84ebaf29e24038aec7c8880))
* Basic admin panel setup ([b83d4a](https://github.com/itinerare/Mundialis/commit/b83d4a97a3a998596fc6d8e73794e5afff66d3a7))
* Basic auth setup and views ([725ed8](https://github.com/itinerare/Mundialis/commit/725ed8b6f512717ed428237da00d9a6bad30c94f))
* Basic site page setup ([b5f8bf](https://github.com/itinerare/Mundialis/commit/b5f8bf5cbf6b2263a1e3fcab51d4055b3fec09cb))
* Clean up widgets ([dfcd60](https://github.com/itinerare/Mundialis/commit/dfcd6086a72295facecb73501b82b1f0b163da4d))
* Clearer instructions around template editing ([3fdd4e](https://github.com/itinerare/Mundialis/commit/3fdd4e19d23e802fe14d8c45dba331d8d845fce1))
* Helper functions ([4073cf](https://github.com/itinerare/Mundialis/commit/4073cf0ccab141d7353dee5938bd39b033db934e))
* Implement basic template editor for subjects ([3f7628](https://github.com/itinerare/Mundialis/commit/3f76287486ab51ac45e8ac900e8072ed4eb4b04f))
* Implement cascading changes for templates ([aaf235](https://github.com/itinerare/Mundialis/commit/aaf235471e01a1aac22f7e580a3450136462843d))
* Implement cascading for category templates ([1fd9a7](https://github.com/itinerare/Mundialis/commit/1fd9a7479e45b02b44158cff70a377fbde108f8b))
* Implement category sorting ([6740c4](https://github.com/itinerare/Mundialis/commit/6740c4a64c9f6279c28d6a075b6469df237fad2e))
* Implement more basic perms checks and route setup ([d64c25](https://github.com/itinerare/Mundialis/commit/d64c25474f64efc566649ceb57db5ed4ba0106b6))
* Implement rank editing ([90d9f9](https://github.com/itinerare/Mundialis/commit/90d9f9357156e9b9ce190ab3751afdda650509be))
* Navbar, dashboard updates ([356e5e](https://github.com/itinerare/Mundialis/commit/356e5e20c81b346ae8ba44fa88689566a5f4eb2c))
* Replace placeholder images with solid colour ([0a15d3](https://github.com/itinerare/Mundialis/commit/0a15d30d15e60b992c7d8e18e30ce392af6018a9))
* Start of admin template panels/routes ([65a32f](https://github.com/itinerare/Mundialis/commit/65a32f6202e4ea2416bf3f74b057e73f96fd8fe5))
* Tos/privacy policy pages/fix them ([7d7480](https://github.com/itinerare/Mundialis/commit/7d7480430bbebabea901b86edd647db06ace41db))
* Update subjects config, rename subject view ([6dd20b](https://github.com/itinerare/Mundialis/commit/6dd20b8ced15cdbfba7acb18c100ad58a55556cb))

##### Auth

* Add admin user edits, email functions ([7aae88](https://github.com/itinerare/Mundialis/commit/7aae885325cf7d7405a92903006de45afc8e764b))
* Add page delete notification ([7d3241](https://github.com/itinerare/Mundialis/commit/7d32411ae567bb56538fb8b12169475fd27f93da))
* Adjust user/acc page title formatting ([dae788](https://github.com/itinerare/Mundialis/commit/dae788805c687a7d0c118cf8fce546e5e6d8f9e6))
* Implement user profiles ([3f2eb0](https://github.com/itinerare/Mundialis/commit/3f2eb0e357b2725949838b6c1f8b3c8d861c4b2f))
* Implement user settings ([092fb6](https://github.com/itinerare/Mundialis/commit/092fb65ca06efe2f888dde2ccf4e7ac414dc34f6))
* Invitation code system ([b052d3](https://github.com/itinerare/Mundialis/commit/b052d30edd10267d28acc2d222c4f38a693e1d0f))
* Notification system ([3eeaab](https://github.com/itinerare/Mundialis/commit/3eeaabeb8b0adba792dd6f7e658b6e4072bd9447))

##### Lang

* Add category settings population ([e208b5](https://github.com/itinerare/Mundialis/commit/e208b51d67e661d9520f5062f027aab427cb766e))
* Add etymology and display to entries ([5fa464](https://github.com/itinerare/Mundialis/commit/5fa46477615d81eeb51b281429d22cd0d68e22a2))
* Add lexicon settings and categories ([f4c806](https://github.com/itinerare/Mundialis/commit/f4c806456678ac739a04a89e2f236372b2f7a46b))
* Add page linking in definitions ([4123ee](https://github.com/itinerare/Mundialis/commit/4123eef5a562f48cf8cdc8a28e7f1023191b6c98))
* Add recursive entry descendant listing ([d3ca77](https://github.com/itinerare/Mundialis/commit/d3ca775f397525675d53e4b4112b3e9e2f4e8a74))
* Add sort by meaning to entry index ([ac6b05](https://github.com/itinerare/Mundialis/commit/ac6b053b676f0eafd3b5cf7043fe858800a4f310))
* Capitalize ucfirst()'d entry autoconj ([6f99e2](https://github.com/itinerare/Mundialis/commit/6f99e2089148c12048f83501d75869e549055906))
* Easier editing of auto-conj rules ([1bedd5](https://github.com/itinerare/Mundialis/commit/1bedd5dc1d531d6187996160ad42b4f19073873c))
* Entry conjugation/declension, display ([0c25cb](https://github.com/itinerare/Mundialis/commit/0c25cbb3548007406b8fc4cfa5380832c23f0003))
* Finish adding category delete and sort ([f191f9](https://github.com/itinerare/Mundialis/commit/f191f920444cb3765e6b8571079bd491866dff73))
* Lexicon entry display and creation ([fb783c](https://github.com/itinerare/Mundialis/commit/fb783cdb71e6a0f6ee9b55bd3d3d291a97d64e57))
* Make conj/decl collapsible ([be4243](https://github.com/itinerare/Mundialis/commit/be4243c4c2e02ffafa72f45142717c1889cdf8d7))
* Sort entry descendants ([7c45eb](https://github.com/itinerare/Mundialis/commit/7c45eb3fc0dea53f287e6e451de07b6b6765b2a7))

##### Pages

* Add ability to filter by tag(s) ([9dc371](https://github.com/itinerare/Mundialis/commit/9dc371cb2592ae5b36821a33720bf34bd4967c22))
* Add advanced tags + navboxes ([ddb8c9](https://github.com/itinerare/Mundialis/commit/ddb8c905f20333dcdb2847beed18ca10d39016e9))
* Add auth-dependent visibility ([7fe483](https://github.com/itinerare/Mundialis/commit/7fe4837abaaeaf46ca6b13a4176b8b7b9e1f4a5f))
* Add basic page creation and editing ([6befee](https://github.com/itinerare/Mundialis/commit/6befeea7cde6a6d4a39a16ab1241dfc28f57792e))
* Add basic page display ([94850e](https://github.com/itinerare/Mundialis/commit/94850e288bd450eb07395efc9997ed7749ae0841))
* Add basic search/sort to page indexes ([6443d0](https://github.com/itinerare/Mundialis/commit/6443d081516fc34eef5674dd33ac4e370fe0930e))
* Add disambiguation features ([732140](https://github.com/itinerare/Mundialis/commit/7321408cdb280d281df2fad79b9ee1b366b7952e))
* Add list of recent page/image changes ([27a853](https://github.com/itinerare/Mundialis/commit/27a853328017ca43005a788d75a4edbc5e5ab8f3))
* Add optgroups to image page selection ([4686b7](https://github.com/itinerare/Mundialis/commit/4686b76684af34064cf55c3cc45bb4f4bb2f7439))
* Add page image system ([f68807](https://github.com/itinerare/Mundialis/commit/f688076b7607e08bb2b4b2ae87763eff6f810d9b))
* Add page protection ([e5de77](https://github.com/itinerare/Mundialis/commit/e5de77c272be4ee28112303033804429273e29d6))
* Add protected pages special page ([9bfe95](https://github.com/itinerare/Mundialis/commit/9bfe95d3c077c907a817e96f92bd871e6c8328e2))
* Add reason and minor edit to versions ([82cad6](https://github.com/itinerare/Mundialis/commit/82cad6c0e0bbf1264ff3dca1b9e7e02a452451c1))
* Add revision history, image history ([c736b2](https://github.com/itinerare/Mundialis/commit/c736b2e63ef67f0961b27de8548007d19d1821b7))
* Add unwatched special page ([cc98cd](https://github.com/itinerare/Mundialis/commit/cc98cdb18db9979d9ce9ca86aeb971ce5416008c))
* Add user list special page ([9f0239](https://github.com/itinerare/Mundialis/commit/9f023950c26d00340e4364a85ea36a17bf4165bf))
* Add visibility display to page header ([1daea5](https://github.com/itinerare/Mundialis/commit/1daea5b0751f9c5f37240c7d088e9e70d2aa9379))
* Add wanted pages, what links here ([fe8ebc](https://github.com/itinerare/Mundialis/commit/fe8ebc9d5617dad16ffd81cf8b7739ed9afaf7cb))
* Add wiki-style link parsing ([38bd23](https://github.com/itinerare/Mundialis/commit/38bd2352d25506eca33aba4bebd9cdcdcd6a365c))
* All pages special page ([2fcc70](https://github.com/itinerare/Mundialis/commit/2fcc7015eace2a86afc2efc99e88007095926ad9))
* All tags list, all images list ([abcbe6](https://github.com/itinerare/Mundialis/commit/abcbe60ac318cc0cde593bfcdc8651e6d9c2b0f5))
* Auto table of contents ([28ee51](https://github.com/itinerare/Mundialis/commit/28ee517ca867fb4e46d828a5f32df63d690e0b22))
* Better org of category/page indices ([950f9b](https://github.com/itinerare/Mundialis/commit/950f9b109a1e6bce9bcfd6abd44ddc7ccf549703))
* Flow for creating wanted pages ([d4fdc1](https://github.com/itinerare/Mundialis/commit/d4fdc1c0b0ca85d2ce9f93772a0a53e0585c9e20))
* Implement page tags ([457b1e](https://github.com/itinerare/Mundialis/commit/457b1e54b1eab6b6c656a33241915838c70c2128))
* Implement specialized subject fields ([94a7d9](https://github.com/itinerare/Mundialis/commit/94a7d92f91f324ed019829a744b25da6267e57bc))
* Implement updates on wanted page creation ([0a230a](https://github.com/itinerare/Mundialis/commit/0a230a87ae696837a0f026828f46b60e6ce473ed))
* Implement utility tags ([d99eee](https://github.com/itinerare/Mundialis/commit/d99eeeefc3b9fedce9a8f299c1313d0e46c40288))
* Make sections collapseable ([548ab1](https://github.com/itinerare/Mundialis/commit/548ab1fcb407f05b1369ec026a2b8fa4f19cc321))
* More precise people age calculation ([5aede2](https://github.com/itinerare/Mundialis/commit/5aede2b8e3716c1c42119ff7170d95406f64f6e8))
* Most/least revised pages list ([994c96](https://github.com/itinerare/Mundialis/commit/994c96afec9c74e06b1059b4a7c812113f724ac0))
* Most linked-to pages special page ([d5fa0c](https://github.com/itinerare/Mundialis/commit/d5fa0cb5e7071b75c933344c790bbb0fba042fd5))
* Most tagged/untagged special pages ([f34a93](https://github.com/itinerare/Mundialis/commit/f34a93a764e97315b68d3139f38b7423e06a7d8e))
* Page links display summary on hover ([66b003](https://github.com/itinerare/Mundialis/commit/66b003439734d33c2a09cf18d4bd13c460732e30))
* Page moving ([9ccfe7](https://github.com/itinerare/Mundialis/commit/9ccfe745fd0c2ce945807e28be2c32a93861b652))
* Subject and category index views ([5fc5e0](https://github.com/itinerare/Mundialis/commit/5fc5e0ee838578fcf63e9c2de921f6de8676459a))

##### People

* Add basic relationship tracking ([ee2c63](https://github.com/itinerare/Mundialis/commit/ee2c6381baa7319ab3f170c3a09c91372ac9cae5))
* Adjust ancestry styling ([20f141](https://github.com/itinerare/Mundialis/commit/20f1412490c1b50a35c061be8f8508cf8e79cb40))
* Basic family tree/listing page ([eb0ab9](https://github.com/itinerare/Mundialis/commit/eb0ab97b2b4865c5cd2eb2ed46f5b3b2f0d455db))

##### Resources

* Add a bunch of basic resources ([bd2eeb](https://github.com/itinerare/Mundialis/commit/bd2eeb2f8dfd4e11377aa9cb4f38d58a97e5fea2))
* Add cache clear to update command ([4a31a7](https://github.com/itinerare/Mundialis/commit/4a31a7d62745d494b84ef048d0f5a4917a143e57))
* Add command to generate changelog ([b4e2b8](https://github.com/itinerare/Mundialis/commit/b4e2b80b22bb269965bd4ceb620d27b5658ea821))
* Add lightbox UI images ([017ee3](https://github.com/itinerare/Mundialis/commit/017ee3ba1fc5405d0a3652d627a8b9fed72c49d5))
* Add settings config ([9ee9d3](https://github.com/itinerare/Mundialis/commit/9ee9d324d4bb690c4a8f8bc6e7a64803f184986f))
* Track composer.lock ([c79b77](https://github.com/itinerare/Mundialis/commit/c79b778ba108d78f051ca8aa99c006b911fc987e))
* Update composer.lock ([9a954a](https://github.com/itinerare/Mundialis/commit/9a954aa23a6ae35b5736d816dcb09f846d166f1c))

##### Tests

* Add event/timeline tests ([68becc](https://github.com/itinerare/Mundialis/commit/68becc11b5ce38b2ecfbb2059306b9f9535d2d87))
* Add initial access tests ([f197eb](https://github.com/itinerare/Mundialis/commit/f197eb63e6442e752d467b3313ec8e6cbb9cecc3))
* Add page delete tests with images ([705a7e](https://github.com/itinerare/Mundialis/commit/705a7e30bae965781b44c0ab7ce68d7440729648))
* Add page image delete tests ([50fd5d](https://github.com/itinerare/Mundialis/commit/50fd5dba60be0abf32edbcb867ce6573c6e26832))
* Add page image update notif test ([d705e6](https://github.com/itinerare/Mundialis/commit/d705e6db6592ca64490ade37f4f0aa8303a95ea9))
* Add page link tests, factory ([a9a874](https://github.com/itinerare/Mundialis/commit/a9a87408f864557bd93299811f3430405e345a42))
* Add page watch tests ([8c7403](https://github.com/itinerare/Mundialis/commit/8c7403b94ac2842701afa28ba1f39aa9b9c7fe44))
* Add subject category index tests ([e4eb5d](https://github.com/itinerare/Mundialis/commit/e4eb5d610c34f032f392a54d5e980ec8a17a4cda))
* Add subject category view tests ([c58709](https://github.com/itinerare/Mundialis/commit/c58709213ae51334794608d06eea2e2e1c5d8983))
* Add used invitation code auth test ([a4a0e6](https://github.com/itinerare/Mundialis/commit/a4a0e67b15267bfb01147bba219ea0fe94124c37))
* Admin function tests ([60ec2e](https://github.com/itinerare/Mundialis/commit/60ec2e7c2098fe2f3fa0c035f0d74452c02363dc))
* Auth and user function tests ([8534c1](https://github.com/itinerare/Mundialis/commit/8534c13c3f4cb022d47c5d4063eb461c6168e556))
* Basic page delete/restore tests ([7e412a](https://github.com/itinerare/Mundialis/commit/7e412adb945cb8282ba02a96cfd782ea1704f54b))
* Basic page tests ([3bcf27](https://github.com/itinerare/Mundialis/commit/3bcf279513dd8e88cd1125af36d62f6c59f23ebb))
* Expand page image tests ([989088](https://github.com/itinerare/Mundialis/commit/9890886ee216a925ee912a0e8c6bc9c442b35ac1))
* Expand user notification tests ([31397b](https://github.com/itinerare/Mundialis/commit/31397b603ee0555157c3aa4f246d4d7c57cfa8f5))
* Expriment with using artisan test ([f4ba2f](https://github.com/itinerare/Mundialis/commit/f4ba2f2959fd23ab606feb5ebf907e463a262f3d))
* Extend special page tests ([04598d](https://github.com/itinerare/Mundialis/commit/04598d505f954fe50ecd0db5abf56f5603bff29a))
* File cleanup in image delete tests ([c02349](https://github.com/itinerare/Mundialis/commit/c0234960bae4415636165dcf69cf2dc2a3c7a79b))
* Lexicon entry tests ([f70698](https://github.com/itinerare/Mundialis/commit/f70698dda4da267deb0d38952a42553842ed510c))
* Page move tests ([790889](https://github.com/itinerare/Mundialis/commit/790889e2f8b92ddb95a010ab13fe9570a5d817b7))
* Page protection tests, factory ([782117](https://github.com/itinerare/Mundialis/commit/782117f91add6d652b474eb71c7af5d03f328bc2))
* Page relationship tests and factory ([92b3ed](https://github.com/itinerare/Mundialis/commit/92b3ed0caf26829ad8d391252f5b34de74525460))
* Page view tests ([07d0a9](https://github.com/itinerare/Mundialis/commit/07d0a9b32f0d7a4cf31749a79d38ba21f0db4c7b))
* Special and subject page tests ([5846f4](https://github.com/itinerare/Mundialis/commit/5846f48b753febb80925bc6ce0b7e61eefdb5edd))
* Start of page image tests, factories ([a2d7d0](https://github.com/itinerare/Mundialis/commit/a2d7d05dc859f18737db0e08310810e2e90461dd))
* Subject data tests ([7b77fb](https://github.com/itinerare/Mundialis/commit/7b77fbacbaa8302ceefd461cb16858bd629a5929))

##### Time

* Add timeline display ([619fa5](https://github.com/itinerare/Mundialis/commit/619fa57e724532e6aae7028143c94c16489f9cb1))
* Add 'use for dates' check to time divisions ([eda3bd](https://github.com/itinerare/Mundialis/commit/eda3bd29f236e451731228a0f4804a97c8ee9319))
* Chronologies display ([9d54d5](https://github.com/itinerare/Mundialis/commit/9d54d50fdc5b595953e81402dee78f9c92a71c51))
* Implement chronology & time divisions ([fd5caf](https://github.com/itinerare/Mundialis/commit/fd5cafa33061912d800112754aa3f4c331df8b52))

##### Users

* Add user factory ([bd609f](https://github.com/itinerare/Mundialis/commit/bd609f120d1f624f5df1e7c78908d76b9aa461e6))

### Bug Fixes

* Admin user routes clobber rank/invite ones ([eed999](https://github.com/itinerare/Mundialis/commit/eed999f329872cdfa1d25ba638c43f75628e9754))
* Cannot upload site images ([699ab0](https://github.com/itinerare/Mundialis/commit/699ab04115ca9de93aa647d0b949fa8d5b27c04c))
* Cascading field/widget template changes error ([0e7337](https://github.com/itinerare/Mundialis/commit/0e73377344da24abc3b1fb58cc39b050eaddb16c))
* Categories indexes list view group incorrectly ([dd3759](https://github.com/itinerare/Mundialis/commit/dd3759946e46ebf25a33b2eb9bea128af4a01f3b))
* Concepts typo ([b7d998](https://github.com/itinerare/Mundialis/commit/b7d99828d150dc147f432c94464fbe4e0d2452ef))
* Error cascading some template changes ([898ddb](https://github.com/itinerare/Mundialis/commit/898ddb1cbe6ec43119707600cf69911ad08bf6ed))
* Error cloning template rows ([080596](https://github.com/itinerare/Mundialis/commit/080596ce17059266fe2d011749b245060ee160c7))
* Error deleting category with deleted pages ([8bfc78](https://github.com/itinerare/Mundialis/commit/8bfc78b686745ad050c11688a30a82eaec0b219c))
* Error deleting subject categories ([813a86](https://github.com/itinerare/Mundialis/commit/813a86ddf41bb32537cbcda51bae75a426ce48ce))
* Error editing subject categories ([b35834](https://github.com/itinerare/Mundialis/commit/b3583453a9e39db22956e208bdf575298392e07d))
* Infobox 'add field' button at top ([a5c78a](https://github.com/itinerare/Mundialis/commit/a5c78a70dc9913da1eb0f267187fcdeb5208002e))
* Infobox field validation rule issues ([4891b9](https://github.com/itinerare/Mundialis/commit/4891b91d97ee40b61a08689069b830804c35bf91))
* Issue detecting field changes as removals ([47abc8](https://github.com/itinerare/Mundialis/commit/47abc871203cb90ac8f7bc2c2d0ead3fef0c0cc4))
* Lang, time page titles nonspecific ([f14180](https://github.com/itinerare/Mundialis/commit/f14180fdbd0b6d8bfd71cf56ad6e6f82d5938f9b))
* Logo present in mobile sidebar ([a199cc](https://github.com/itinerare/Mundialis/commit/a199cc6ed5e391eed9aaefdcd5312c0d0d292119))
* Minor template builder issues ([7fe693](https://github.com/itinerare/Mundialis/commit/7fe6937a102e704ba3c6bbd19f05116637497de1))
* Misc tidying and fixing ([434e5b](https://github.com/itinerare/Mundialis/commit/434e5b79145c906d295f6ef231f0ce9aea13abb2))
* Read check errors when logged out ([3ec976](https://github.com/itinerare/Mundialis/commit/3ec976c9eba1a67eef485931f53853259afd3a80))
* Removing template section removes all of them ([78ad4a](https://github.com/itinerare/Mundialis/commit/78ad4a6110e8db9e9d410a4d47ca97acc0da814f))
* Section 'add field' button also at top ([2efc74](https://github.com/itinerare/Mundialis/commit/2efc7427fcb020d9bfbca279bbf94f09fc8e1b24))
* Setup-admin reset out of order ([485b2a](https://github.com/itinerare/Mundialis/commit/485b2af675edb0a2bb406a26494cad51cadca39f))
* TinyMCE won't initialize ([c2370a](https://github.com/itinerare/Mundialis/commit/c2370aa4fb0ef2538f3493f5ae35440509eb2a87))
* Typo in subjects config file ([0d7fa2](https://github.com/itinerare/Mundialis/commit/0d7fa2527a25716155025ac055fef64dbf88434b))
* Update command not declared properly ([90beda](https://github.com/itinerare/Mundialis/commit/90beda5868c93664505cee6106ac780e9c8bca54))
* Validation errors do not appear ([30330c](https://github.com/itinerare/Mundialis/commit/30330c4946afc24c0a0319f0fba542bc981bed3c))

##### Auth

* Extraneous user settings alert ([8ff4b5](https://github.com/itinerare/Mundialis/commit/8ff4b5d8b6625823f731938d2cbe073429aa8b68))
* Watched pages title incorrect ([618670](https://github.com/itinerare/Mundialis/commit/618670adeccadeb5f830b62cd33506f65d2158ba))

##### Dep

* Composer.json version string ([7ac90a](https://github.com/itinerare/Mundialis/commit/7ac90a9461180d792f679caa4a2d09d9a7b04cfc))

##### Lang

* Add missing auth check to entry index ([d2baa2](https://github.com/itinerare/Mundialis/commit/d2baa277cd67c8b25d88e1779b0902e6306c4e4e))
* Can't edit categories w/o properties ([7f09cc](https://github.com/itinerare/Mundialis/commit/7f09ccbe2af4dc292fe9a8b02849850274dc4506))
* Category conj/declen order inconsistent ([3279f1](https://github.com/itinerare/Mundialis/commit/3279f156d95ddc0b34e7786a5729efce6f868196))
* Category deletion uses wrong function ([0e2583](https://github.com/itinerare/Mundialis/commit/0e2583658f74d1da932d4523f203ae83a8df772d))
* Category edit button links to wrong page ([b3ecc6](https://github.com/itinerare/Mundialis/commit/b3ecc6fabffa0ad4af4ac19f75b856a7ff7651b5))
* Category table display wonky ([603464](https://github.com/itinerare/Mundialis/commit/603464d222f2306035bebbff4c500beec7012f7d))
* Creation of empty etymology records ([aa6e42](https://github.com/itinerare/Mundialis/commit/aa6e42f8dcddbbad9f22af381229f7edd667ae31))
* Errant dd() ([3a0240](https://github.com/itinerare/Mundialis/commit/3a02406ebadc59238e64ba2209f6afd7b2548a74))
* Error creating/editing entries ([51f80a](https://github.com/itinerare/Mundialis/commit/51f80ad112d8aad03e76c00f62b9bfe4b2269e5f))
* Error editing entries w/o conj/decl ([326d55](https://github.com/itinerare/Mundialis/commit/326d5525cd1e8ec1341f879fca0b1b9f54ee3285))
* Error updating entry etymology ([be922f](https://github.com/itinerare/Mundialis/commit/be922f8112f0000cdd1f3dcae7332111ed29d699))
* Error viewing categoryless entries ([8bd4ed](https://github.com/itinerare/Mundialis/commit/8bd4edec1bf17cddc65065c51b492d5bb343463c))
* Etymology entry def is all lowercase ([fdae6e](https://github.com/itinerare/Mundialis/commit/fdae6e13025883424ab02d27db6b24db80ca7af2))
* Etymology shows own lex class ([721987](https://github.com/itinerare/Mundialis/commit/721987e590ab654d9d4206c920b83c095b5d7572))
* Issue w conj/decl autogen ([3eb780](https://github.com/itinerare/Mundialis/commit/3eb7803e1ab5c2b3937de8897ab67fe97edea7f2))
* New entry parsed description not saved ([0ffdcc](https://github.com/itinerare/Mundialis/commit/0ffdcc3e9a835530e6ec3c9c897d02ed0cd89fc0))
* Wiki link parse error on create entry ([8b9b03](https://github.com/itinerare/Mundialis/commit/8b9b03ca5a58ecd35e1b5e2e4e42ec4b12a66399))

##### Language

* More checking on entry deletion ([39e83f](https://github.com/itinerare/Mundialis/commit/39e83fa589c2c059a1c80edc5ca7dc00603c7608))

##### Pages

* Add check for image page attachment ([43c4e4](https://github.com/itinerare/Mundialis/commit/43c4e4dacc0a4875f4effb7761e5b6dcd31e12ac))
* Allow dashes in parsed links ([ec5b6f](https://github.com/itinerare/Mundialis/commit/ec5b6f149d1bd79709196fd76d5c45e47858338e))
* Auto-ToC width wonky on mobile ([c629f8](https://github.com/itinerare/Mundialis/commit/c629f88362d61173ca7fd70a83c46aef402217bc))
* Better relationship creation checks ([b4f8d9](https://github.com/itinerare/Mundialis/commit/b4f8d96efcdf6b239f9086af5bec8ca4d9f84259))
* Cannot remove extra links from images ([7d031e](https://github.com/itinerare/Mundialis/commit/7d031e747232b1435855c1f83e67bf9549a15f23))
* Cannot set page non-visible ([578712](https://github.com/itinerare/Mundialis/commit/57871216fc47757ae8da22fc382ed2b5302909f0))
* Category optgroups sorted wrong ([35e1ed](https://github.com/itinerare/Mundialis/commit/35e1ed905c03b7e532831f2c537db2786d56bd63))
* Check image has pages when restoring ([c91f53](https://github.com/itinerare/Mundialis/commit/c91f53632a8245fd0052813b3e306784bf17cb35))
* Create image active toggle not shown ([ee8dfa](https://github.com/itinerare/Mundialis/commit/ee8dfab142b471e30c947e5e0dc73dc514c52c70))
* Current page is in image edit pages ([496ee7](https://github.com/itinerare/Mundialis/commit/496ee7aebdd3e73992a4ffdfb6a69a247cfdd0c7))
* Data column not nullable ([b7b0ed](https://github.com/itinerare/Mundialis/commit/b7b0ed9b4159cc1f4caa711a8fb0cb781390219e))
* Deleted page/image results in recent edits ([4c71f8](https://github.com/itinerare/Mundialis/commit/4c71f88413de4637b7e1b0f8662bc37a79ffb5a2))
* DisplayName formatting ([9bbfe7](https://github.com/itinerare/Mundialis/commit/9bbfe7efcc21ce8a7d920615d7fe1df85424ccac))
* DisplayName formatting error ([fbba2f](https://github.com/itinerare/Mundialis/commit/fbba2f178185a3e1d77d1ea5600c755b12cbee2f))
* Edit/reset allow deleted page title ([aec9de](https://github.com/itinerare/Mundialis/commit/aec9de61db75f8138e66349f9e2269ab608324c4))
* Error creating pages ([de4c42](https://github.com/itinerare/Mundialis/commit/de4c42a86f9ce6d58415441625830459cf8c3871))
* Error fetching page data from version ([3e6ccb](https://github.com/itinerare/Mundialis/commit/3e6ccb26d0f3517797f04a45609dbebe36f8ff85))
* Error restoring page ([08bce5](https://github.com/itinerare/Mundialis/commit/08bce56cacb89aef5fde51e6313a2b4778a88d05))
* Error uploading custom image thumbnail ([faae12](https://github.com/itinerare/Mundialis/commit/faae127be0d22a56a7fd7844280593c1ba20c5eb))
* Error viewing categories ([878c97](https://github.com/itinerare/Mundialis/commit/878c97f681eac8d140dd0d9a330655b673c16991))
* Error viewing recent page/image widget ([b387a1](https://github.com/itinerare/Mundialis/commit/b387a19d40a8ca0c7fbebc7cde367d62a62d7006))
* Force-deleted pages error changes list ([98f958](https://github.com/itinerare/Mundialis/commit/98f95886d6b1a3e2f59c28fa8a3ea759dced6279))
* Gallery formatting slightly wonky ([1153b4](https://github.com/itinerare/Mundialis/commit/1153b4c9ef608a94e8fb5c07e03301eff5a16987))
* Handle link parse special chars better ([3bc7e2](https://github.com/itinerare/Mundialis/commit/3bc7e24babac2513101e0e8624a511cbbbe15713))
* Hidden pages visible in navbox ([52b7db](https://github.com/itinerare/Mundialis/commit/52b7db8580b1f3bdb413732a83640edfbd206634))
* Image create/edit verbiage, processing ([e82058](https://github.com/itinerare/Mundialis/commit/e82058618c807037afbe48e3fb630d08bfbab3ec))
* Image files aren't deleted properly ([f7380d](https://github.com/itinerare/Mundialis/commit/f7380d6aa6be16884f54ea6a001a9da6ebc89fc5))
* Image height in popup not capped ([b6fed7](https://github.com/itinerare/Mundialis/commit/b6fed7953b0a2d0ff8a75f91d9c5b24bc273a759))
* Image info formatting tweak ([543ee8](https://github.com/itinerare/Mundialis/commit/543ee8136b44bc5b3a84dc22b351697aba681594))
* Image revision log takes up more space ([e45865](https://github.com/itinerare/Mundialis/commit/e458651c373beee3412602f8448f37799d75157e))
* Images in info popup/page not centered ([9282ca](https://github.com/itinerare/Mundialis/commit/9282cadad8fcb28e8ed5ac31c804ff04d7d6e272))
* Image unlink deletes page not detaches ([d3ae71](https://github.com/itinerare/Mundialis/commit/d3ae71900e32311387ad8d81c34f13f8ba73feab))
* Introduction crowds infobox ([8c6c3e](https://github.com/itinerare/Mundialis/commit/8c6c3e1ad293de539fdb8fc116a3cebd90eab28b))
* Issue setting image creator url ([8f5d34](https://github.com/itinerare/Mundialis/commit/8f5d347a77023c2ae6fe87041f7055f0d7d75c11))
* Link parse detection issue, dupe links ([72c475](https://github.com/itinerare/Mundialis/commit/72c4751a8d1118ce4c272acd7484494d1f45b1d1))
* Link parse issues for people, disambig ([ab7d77](https://github.com/itinerare/Mundialis/commit/ab7d77b2275c79573bd3eab1833a84c816160ac3))
* Link parsing error ([142392](https://github.com/itinerare/Mundialis/commit/14239233a77b1e1b0c1ae06c247d52b81d7d1438))
* Minor infobox field display issue ([7784fb](https://github.com/itinerare/Mundialis/commit/7784fb4955a51d2f05c2018e1b2dcc38ac50aec1))
* Minor whitespace adjustment to service ([020d75](https://github.com/itinerare/Mundialis/commit/020d754f58eb70d9f17f24a796ee35111604e4e4))
* More consistent img creator processing ([4afabc](https://github.com/itinerare/Mundialis/commit/4afabc8b80c72de39d1b7a30fba3957ed8cc6ba9))
* Navbox displays deleted pages ([251163](https://github.com/itinerare/Mundialis/commit/2511635fa9d858825a365025cf0ebe2433adf3f5))
* Navbox doesn't display subcat pages... ([8329fb](https://github.com/itinerare/Mundialis/commit/8329fb11fe7f7c8dec1d72011c2b5701cc3f06df))
* Navbox errors when no context pages ([486c47](https://github.com/itinerare/Mundialis/commit/486c4718f01da90c02cb114892c21b3613a112ff))
* Page image update notif not being sent ([621c30](https://github.com/itinerare/Mundialis/commit/621c301762442b8450db361f748bac74ca1a2988))
* Page index top pagination out of order ([9cf99b](https://github.com/itinerare/Mundialis/commit/9cf99b96d51a620482199b5e92079f909c2d9e31))
* Page link detection broken ([2f1e45](https://github.com/itinerare/Mundialis/commit/2f1e45a8c777dd526be7bda3521fdb8c99080957))
* Page list cols try to nest ([a9d334](https://github.com/itinerare/Mundialis/commit/a9d3344243cbd84e44ec738fb33b8ec2274d62c2))
* Parent place field populated wrong ([511604](https://github.com/itinerare/Mundialis/commit/51160477defeea2b3c8d6c79396ad600e275aeec))
* Parsed link inconsistent w displayName ([8fc3b3](https://github.com/itinerare/Mundialis/commit/8fc3b3968a64b0d6d1a8440ed67d0ed8c59a42ce))
* Parsed links don't display in infobox ([a59195](https://github.com/itinerare/Mundialis/commit/a59195ea51eff9c16ee3bded1ce04827faecb8eb))
* Random page errors when no pages ([a4056d](https://github.com/itinerare/Mundialis/commit/a4056d5b13f4a63045dd5b51de33352d154a59ce))
* Relationships not force deleted w page ([f5323d](https://github.com/itinerare/Mundialis/commit/f5323d12ddba580b82be43e2b7d868b4aa8b3d5a))
* Routing error ([893f3a](https://github.com/itinerare/Mundialis/commit/893f3a43f30f52ddb571ec9fb2ffe13f17e055af))
* Section length calc doesn't add right ([904a6a](https://github.com/itinerare/Mundialis/commit/904a6a1bcce147ac7f100bc749bf68cc0af58cb1))
* Subject breadcrumbs links outdated ([c86294](https://github.com/itinerare/Mundialis/commit/c862949f09b079afc0bb0be12a84a5704f332faa))
* Subject query inexplicably hardcoded ([b8caa1](https://github.com/itinerare/Mundialis/commit/b8caa1cf938e7278c69909ecd93b6903fcd5ecb1))
* Subject routes not adjusted ([835e57](https://github.com/itinerare/Mundialis/commit/835e57f39782871006f1a5baa21aae9ea8dd48f8))
* Tag field not labeled optional ([d0b508](https://github.com/itinerare/Mundialis/commit/d0b5087ff68673a99ea8c58e1af01be3196122b7))
* Timeline errors if no date divisions ([df67bd](https://github.com/itinerare/Mundialis/commit/df67bd5d7f68cedcc1673ff963c0caaff5d2405d))
* Undo overzealous change ([89e352](https://github.com/itinerare/Mundialis/commit/89e352ee0fccfc53d9829484a51efc0722ab2593))
* Version data displaying incorrectly ([532103](https://github.com/itinerare/Mundialis/commit/532103cccbbb3c807d96c7ed602ceb0e4da193db))
* Wiki link parsing backwards ([751804](https://github.com/itinerare/Mundialis/commit/751804f6670f913265f368018595c0a0d446412f))

##### People

* Error viewing relationships ([db68ae](https://github.com/itinerare/Mundialis/commit/db68ae25cc34e667163821c537edd23d9867ec89))
* Incorrect form order when creating relationships ([6d1b48](https://github.com/itinerare/Mundialis/commit/6d1b48a3775078ebd950e7975b0753b272d3512b))

##### Resources

* Changelog command has wrong description ([ff1859](https://github.com/itinerare/Mundialis/commit/ff1859528074ce1d44be37ea75e3ea725890a803))

##### Tests

* Add failsafe site page generation ([c3e5e7](https://github.com/itinerare/Mundialis/commit/c3e5e7491225e271b4b0d99a580680d53a934dce))
* Error running basic feature test ([748af1](https://github.com/itinerare/Mundialis/commit/748af1d0b1b40922ab5c5dee9c96887388bfaa69))
* Error running tests ([b9cbbf](https://github.com/itinerare/Mundialis/commit/b9cbbfd214b8827e5211206dae0286f3be8463ed))
* Error running tests on fresh DB ([4658d0](https://github.com/itinerare/Mundialis/commit/4658d0556021bdff7bb8a099300eea7333276f45))
* Image update notif test error ([6581ce](https://github.com/itinerare/Mundialis/commit/6581ce59ebd0fea894a9b2d6ef18949b56143286))
* Remove unit test, since currently empty ([a796b1](https://github.com/itinerare/Mundialis/commit/a796b1bdbc1e39e2da04da59e7ee9f5665c6c31b))

##### Time

* Chronology editing points at category editing ([20b89f](https://github.com/itinerare/Mundialis/commit/20b89f4f954b3bdc27cdfe75f3c8841b15dcb1a0))
* Chronology index table displays wonky ([086068](https://github.com/itinerare/Mundialis/commit/086068a5aada957adb6078f1eb9c485339ddd6f0))
* Error sorting chronologies ([541150](https://github.com/itinerare/Mundialis/commit/54115014b2f62220236bb463e28fe9455906d04d))
* Fix error editing divisions ([68ef60](https://github.com/itinerare/Mundialis/commit/68ef605bab10629347d4778ff32e8f1d437e0507))
* Renaming time divisions would delete/recreate ([98760d](https://github.com/itinerare/Mundialis/commit/98760d898ea0f25d38dbf58c6aa3903b4623e85a))

##### Users

* 2FA confirm/disable functions absent ([2e1e89](https://github.com/itinerare/Mundialis/commit/2e1e89283bce4490227996d91b208f61d506402c))
* Add check for closed reg when creating ([fd2b25](https://github.com/itinerare/Mundialis/commit/fd2b2502927a4d383979b464139775fffc7949ab))
* Cleaner canWrite, admin checks ([d2781c](https://github.com/itinerare/Mundialis/commit/d2781cd4c1740b5999d42aa6e616f950820ff8f7))
* Error banning user ([696001](https://github.com/itinerare/Mundialis/commit/6960011f2e699f74698b30815c98ad1d6889e78f))
* Error clearing notifications of type 0 ([49ded1](https://github.com/itinerare/Mundialis/commit/49ded1d10314a705967f86821724c0c67cfb1bf7))
* Error unbanning user ([c45f25](https://github.com/itinerare/Mundialis/commit/c45f25c9ac8e8daa979b123a49b28fb74273e563))
* Further improved rank checks/fetching ([205f48](https://github.com/itinerare/Mundialis/commit/205f4872a1a34065c9579e987b6599f65f5f9fee))
* Incorrect account sidebar link ([1a56a1](https://github.com/itinerare/Mundialis/commit/1a56a16398aa4ea61027488804acd54107d0a460))
* More consistent canWrite check ([b6dcd7](https://github.com/itinerare/Mundialis/commit/b6dcd76bd0c5385bd57da651144c8e35cbce1de2))
* Recent images preview error on profile ([7a1396](https://github.com/itinerare/Mundialis/commit/7a139625214fa1a777a83e92778c63e616bf3673))

---

## [1.0.0-rc1](https://github.com/itinerare/Mundialis/compare/1.0.0-pre4...v1.0.0-rc1) (2021-11-23)
### Features


##### Tests

* Add event/timeline tests ([68becc](https://github.com/itinerare/Mundialis/commit/68becc11b5ce38b2ecfbb2059306b9f9535d2d87))
* Add initial access tests ([f197eb](https://github.com/itinerare/Mundialis/commit/f197eb63e6442e752d467b3313ec8e6cbb9cecc3))
* Add page delete tests with images ([705a7e](https://github.com/itinerare/Mundialis/commit/705a7e30bae965781b44c0ab7ce68d7440729648))
* Add page image delete tests ([50fd5d](https://github.com/itinerare/Mundialis/commit/50fd5dba60be0abf32edbcb867ce6573c6e26832))
* Add page image update notif test ([d705e6](https://github.com/itinerare/Mundialis/commit/d705e6db6592ca64490ade37f4f0aa8303a95ea9))
* Add page link tests, factory ([a9a874](https://github.com/itinerare/Mundialis/commit/a9a87408f864557bd93299811f3430405e345a42))
* Add page watch tests ([8c7403](https://github.com/itinerare/Mundialis/commit/8c7403b94ac2842701afa28ba1f39aa9b9c7fe44))
* Add subject category index tests ([e4eb5d](https://github.com/itinerare/Mundialis/commit/e4eb5d610c34f032f392a54d5e980ec8a17a4cda))
* Add subject category view tests ([c58709](https://github.com/itinerare/Mundialis/commit/c58709213ae51334794608d06eea2e2e1c5d8983))
* Add used invitation code auth test ([a4a0e6](https://github.com/itinerare/Mundialis/commit/a4a0e67b15267bfb01147bba219ea0fe94124c37))
* Admin function tests ([60ec2e](https://github.com/itinerare/Mundialis/commit/60ec2e7c2098fe2f3fa0c035f0d74452c02363dc))
* Auth and user function tests ([8534c1](https://github.com/itinerare/Mundialis/commit/8534c13c3f4cb022d47c5d4063eb461c6168e556))
* Basic page delete/restore tests ([7e412a](https://github.com/itinerare/Mundialis/commit/7e412adb945cb8282ba02a96cfd782ea1704f54b))
* Basic page tests ([3bcf27](https://github.com/itinerare/Mundialis/commit/3bcf279513dd8e88cd1125af36d62f6c59f23ebb))
* Expand page image tests ([989088](https://github.com/itinerare/Mundialis/commit/9890886ee216a925ee912a0e8c6bc9c442b35ac1))
* Expand user notification tests ([31397b](https://github.com/itinerare/Mundialis/commit/31397b603ee0555157c3aa4f246d4d7c57cfa8f5))
* Expriment with using artisan test ([f4ba2f](https://github.com/itinerare/Mundialis/commit/f4ba2f2959fd23ab606feb5ebf907e463a262f3d))
* Extend special page tests ([04598d](https://github.com/itinerare/Mundialis/commit/04598d505f954fe50ecd0db5abf56f5603bff29a))
* File cleanup in image delete tests ([c02349](https://github.com/itinerare/Mundialis/commit/c0234960bae4415636165dcf69cf2dc2a3c7a79b))
* Lexicon entry tests ([f70698](https://github.com/itinerare/Mundialis/commit/f70698dda4da267deb0d38952a42553842ed510c))
* Page move tests ([790889](https://github.com/itinerare/Mundialis/commit/790889e2f8b92ddb95a010ab13fe9570a5d817b7))
* Page protection tests, factory ([782117](https://github.com/itinerare/Mundialis/commit/782117f91add6d652b474eb71c7af5d03f328bc2))
* Page relationship tests and factory ([92b3ed](https://github.com/itinerare/Mundialis/commit/92b3ed0caf26829ad8d391252f5b34de74525460))
* Page view tests ([07d0a9](https://github.com/itinerare/Mundialis/commit/07d0a9b32f0d7a4cf31749a79d38ba21f0db4c7b))
* Special and subject page tests ([5846f4](https://github.com/itinerare/Mundialis/commit/5846f48b753febb80925bc6ce0b7e61eefdb5edd))
* Start of page image tests, factories ([a2d7d0](https://github.com/itinerare/Mundialis/commit/a2d7d05dc859f18737db0e08310810e2e90461dd))
* Subject data tests ([7b77fb](https://github.com/itinerare/Mundialis/commit/7b77fbacbaa8302ceefd461cb16858bd629a5929))

##### Users

* Add user factory ([bd609f](https://github.com/itinerare/Mundialis/commit/bd609f120d1f624f5df1e7c78908d76b9aa461e6))

### Bug Fixes

* Error cascading some template changes ([898ddb](https://github.com/itinerare/Mundialis/commit/898ddb1cbe6ec43119707600cf69911ad08bf6ed))
* Error deleting category with deleted pages ([8bfc78](https://github.com/itinerare/Mundialis/commit/8bfc78b686745ad050c11688a30a82eaec0b219c))
* Misc tidying and fixing ([434e5b](https://github.com/itinerare/Mundialis/commit/434e5b79145c906d295f6ef231f0ce9aea13abb2))

##### Language

* More checking on entry deletion ([39e83f](https://github.com/itinerare/Mundialis/commit/39e83fa589c2c059a1c80edc5ca7dc00603c7608))

##### Pages

* Add check for image page attachment ([43c4e4](https://github.com/itinerare/Mundialis/commit/43c4e4dacc0a4875f4effb7761e5b6dcd31e12ac))
* Better relationship creation checks ([b4f8d9](https://github.com/itinerare/Mundialis/commit/b4f8d96efcdf6b239f9086af5bec8ca4d9f84259))
* DisplayName formatting ([9bbfe7](https://github.com/itinerare/Mundialis/commit/9bbfe7efcc21ce8a7d920615d7fe1df85424ccac))
* DisplayName formatting error ([fbba2f](https://github.com/itinerare/Mundialis/commit/fbba2f178185a3e1d77d1ea5600c755b12cbee2f))
* Error uploading custom image thumbnail ([faae12](https://github.com/itinerare/Mundialis/commit/faae127be0d22a56a7fd7844280593c1ba20c5eb))
* Image create/edit verbiage, processing ([e82058](https://github.com/itinerare/Mundialis/commit/e82058618c807037afbe48e3fb630d08bfbab3ec))
* Issue setting image creator url ([8f5d34](https://github.com/itinerare/Mundialis/commit/8f5d347a77023c2ae6fe87041f7055f0d7d75c11))
* Minor whitespace adjustment to service ([020d75](https://github.com/itinerare/Mundialis/commit/020d754f58eb70d9f17f24a796ee35111604e4e4))
* Page image update notif not being sent ([621c30](https://github.com/itinerare/Mundialis/commit/621c301762442b8450db361f748bac74ca1a2988))
* Parsed link inconsistent w displayName ([8fc3b3](https://github.com/itinerare/Mundialis/commit/8fc3b3968a64b0d6d1a8440ed67d0ed8c59a42ce))
* Relationships not force deleted w page ([f5323d](https://github.com/itinerare/Mundialis/commit/f5323d12ddba580b82be43e2b7d868b4aa8b3d5a))
* Timeline errors if no date divisions ([df67bd](https://github.com/itinerare/Mundialis/commit/df67bd5d7f68cedcc1673ff963c0caaff5d2405d))
* Undo overzealous change ([89e352](https://github.com/itinerare/Mundialis/commit/89e352ee0fccfc53d9829484a51efc0722ab2593))

##### Tests

* Add failsafe site page generation ([c3e5e7](https://github.com/itinerare/Mundialis/commit/c3e5e7491225e271b4b0d99a580680d53a934dce))
* Error running tests on fresh DB ([4658d0](https://github.com/itinerare/Mundialis/commit/4658d0556021bdff7bb8a099300eea7333276f45))
* Image update notif test error ([6581ce](https://github.com/itinerare/Mundialis/commit/6581ce59ebd0fea894a9b2d6ef18949b56143286))

##### Users

* 2FA confirm/disable functions absent ([2e1e89](https://github.com/itinerare/Mundialis/commit/2e1e89283bce4490227996d91b208f61d506402c))
* Add check for closed reg when creating ([fd2b25](https://github.com/itinerare/Mundialis/commit/fd2b2502927a4d383979b464139775fffc7949ab))
* Error banning user ([696001](https://github.com/itinerare/Mundialis/commit/6960011f2e699f74698b30815c98ad1d6889e78f))
* Error clearing notifications of type 0 ([49ded1](https://github.com/itinerare/Mundialis/commit/49ded1d10314a705967f86821724c0c67cfb1bf7))
* Error unbanning user ([c45f25](https://github.com/itinerare/Mundialis/commit/c45f25c9ac8e8daa979b123a49b28fb74273e563))
* Further improved rank checks/fetching ([205f48](https://github.com/itinerare/Mundialis/commit/205f4872a1a34065c9579e987b6599f65f5f9fee))
* Incorrect account sidebar link ([1a56a1](https://github.com/itinerare/Mundialis/commit/1a56a16398aa4ea61027488804acd54107d0a460))

---

## [1.0.0-pre4](https://github.com/itinerare/Mundialis/compare/1.0.0-pre3...1.0.0-pre4) (2021-10-10)
### Bug Fixes


##### Dep

* Composer.json version string ([7ac90a](https://github.com/itinerare/Mundialis/commit/7ac90a9461180d792f679caa4a2d09d9a7b04cfc))

##### Pages

* Minor infobox field display issue ([7784fb](https://github.com/itinerare/Mundialis/commit/7784fb4955a51d2f05c2018e1b2dcc38ac50aec1))

##### Tests

* Error running basic feature test ([748af1](https://github.com/itinerare/Mundialis/commit/748af1d0b1b40922ab5c5dee9c96887388bfaa69))
* Error running tests ([b9cbbf](https://github.com/itinerare/Mundialis/commit/b9cbbfd214b8827e5211206dae0286f3be8463ed))
* Remove unit test, since currently empty ([a796b1](https://github.com/itinerare/Mundialis/commit/a796b1bdbc1e39e2da04da59e7ee9f5665c6c31b))

##### Users

* Cleaner canWrite, admin checks ([d2781c](https://github.com/itinerare/Mundialis/commit/d2781cd4c1740b5999d42aa6e616f950820ff8f7))
* More consistent canWrite check ([b6dcd7](https://github.com/itinerare/Mundialis/commit/b6dcd76bd0c5385bd57da651144c8e35cbce1de2))
* Recent images preview error on profile ([7a1396](https://github.com/itinerare/Mundialis/commit/7a139625214fa1a777a83e92778c63e616bf3673))

---

## [1.0.0-pre3](https://github.com/itinerare/Mundialis/compare/1.0.0-pre2...1.0.0-pre3) (2021-08-22)
### Bug Fixes


##### Pages

* Create image active toggle not shown ([ee8dfa](https://github.com/itinerare/Mundialis/commit/ee8dfab142b471e30c947e5e0dc73dc514c52c70))
* Image height in popup not capped ([b6fed7](https://github.com/itinerare/Mundialis/commit/b6fed7953b0a2d0ff8a75f91d9c5b24bc273a759))

---

## [1.0.0-pre2](https://github.com/itinerare/Mundialis/compare/1.0.0-pre1...1.0.0-pre2) (2021-08-15)
### Features

* Add further .env files to ignore ([532f94](https://github.com/itinerare/Mundialis/commit/532f9445788430018e139fa6673e007e2e0d6010))
* Tos/privacy policy pages/fix them ([7d7480](https://github.com/itinerare/Mundialis/commit/7d7480430bbebabea901b86edd647db06ace41db))

##### Auth

* Adjust user/acc page title formatting ([dae788](https://github.com/itinerare/Mundialis/commit/dae788805c687a7d0c118cf8fce546e5e6d8f9e6))

##### Lang

* Capitalize ucfirst()'d entry autoconj ([6f99e2](https://github.com/itinerare/Mundialis/commit/6f99e2089148c12048f83501d75869e549055906))

##### Resources

* Add cache clear to update command ([4a31a7](https://github.com/itinerare/Mundialis/commit/4a31a7d62745d494b84ef048d0f5a4917a143e57))
* Update composer.lock ([9a954a](https://github.com/itinerare/Mundialis/commit/9a954aa23a6ae35b5736d816dcb09f846d166f1c))

### Bug Fixes

* Cannot upload site images ([699ab0](https://github.com/itinerare/Mundialis/commit/699ab04115ca9de93aa647d0b949fa8d5b27c04c))
* Categories indexes list view group incorrectly ([dd3759](https://github.com/itinerare/Mundialis/commit/dd3759946e46ebf25a33b2eb9bea128af4a01f3b))
* Error cloning template rows ([080596](https://github.com/itinerare/Mundialis/commit/080596ce17059266fe2d011749b245060ee160c7))
* Error deleting subject categories ([813a86](https://github.com/itinerare/Mundialis/commit/813a86ddf41bb32537cbcda51bae75a426ce48ce))
* Infobox 'add field' button at top ([a5c78a](https://github.com/itinerare/Mundialis/commit/a5c78a70dc9913da1eb0f267187fcdeb5208002e))
* Infobox field validation rule issues ([4891b9](https://github.com/itinerare/Mundialis/commit/4891b91d97ee40b61a08689069b830804c35bf91))
* Lang, time page titles nonspecific ([f14180](https://github.com/itinerare/Mundialis/commit/f14180fdbd0b6d8bfd71cf56ad6e6f82d5938f9b))
* Read check errors when logged out ([3ec976](https://github.com/itinerare/Mundialis/commit/3ec976c9eba1a67eef485931f53853259afd3a80))
* Section 'add field' button also at top ([2efc74](https://github.com/itinerare/Mundialis/commit/2efc7427fcb020d9bfbca279bbf94f09fc8e1b24))

##### Auth

* Extraneous user settings alert ([8ff4b5](https://github.com/itinerare/Mundialis/commit/8ff4b5d8b6625823f731938d2cbe073429aa8b68))
* Watched pages title incorrect ([618670](https://github.com/itinerare/Mundialis/commit/618670adeccadeb5f830b62cd33506f65d2158ba))

##### Lang

* Errant dd() ([3a0240](https://github.com/itinerare/Mundialis/commit/3a02406ebadc59238e64ba2209f6afd7b2548a74))
* Error creating/editing entries ([51f80a](https://github.com/itinerare/Mundialis/commit/51f80ad112d8aad03e76c00f62b9bfe4b2269e5f))
* Error editing entries w/o conj/decl ([326d55](https://github.com/itinerare/Mundialis/commit/326d5525cd1e8ec1341f879fca0b1b9f54ee3285))
* Error updating entry etymology ([be922f](https://github.com/itinerare/Mundialis/commit/be922f8112f0000cdd1f3dcae7332111ed29d699))
* Error viewing categoryless entries ([8bd4ed](https://github.com/itinerare/Mundialis/commit/8bd4edec1bf17cddc65065c51b492d5bb343463c))
* Etymology entry def is all lowercase ([fdae6e](https://github.com/itinerare/Mundialis/commit/fdae6e13025883424ab02d27db6b24db80ca7af2))
* Etymology shows own lex class ([721987](https://github.com/itinerare/Mundialis/commit/721987e590ab654d9d4206c920b83c095b5d7572))
* Issue w conj/decl autogen ([3eb780](https://github.com/itinerare/Mundialis/commit/3eb7803e1ab5c2b3937de8897ab67fe97edea7f2))
* New entry parsed description not saved ([0ffdcc](https://github.com/itinerare/Mundialis/commit/0ffdcc3e9a835530e6ec3c9c897d02ed0cd89fc0))
* Wiki link parse error on create entry ([8b9b03](https://github.com/itinerare/Mundialis/commit/8b9b03ca5a58ecd35e1b5e2e4e42ec4b12a66399))

##### Pages

* Allow dashes in parsed links ([ec5b6f](https://github.com/itinerare/Mundialis/commit/ec5b6f149d1bd79709196fd76d5c45e47858338e))
* Current page is in image edit pages ([496ee7](https://github.com/itinerare/Mundialis/commit/496ee7aebdd3e73992a4ffdfb6a69a247cfdd0c7))
* Deleted page/image results in recent edits ([4c71f8](https://github.com/itinerare/Mundialis/commit/4c71f88413de4637b7e1b0f8662bc37a79ffb5a2))
* Error creating pages ([de4c42](https://github.com/itinerare/Mundialis/commit/de4c42a86f9ce6d58415441625830459cf8c3871))
* Error restoring page ([08bce5](https://github.com/itinerare/Mundialis/commit/08bce56cacb89aef5fde51e6313a2b4778a88d05))
* Error viewing recent page/image widget ([b387a1](https://github.com/itinerare/Mundialis/commit/b387a19d40a8ca0c7fbebc7cde367d62a62d7006))
* Handle link parse special chars better ([3bc7e2](https://github.com/itinerare/Mundialis/commit/3bc7e24babac2513101e0e8624a511cbbbe15713))
* Image info formatting tweak ([543ee8](https://github.com/itinerare/Mundialis/commit/543ee8136b44bc5b3a84dc22b351697aba681594))
* Images in info popup/page not centered ([9282ca](https://github.com/itinerare/Mundialis/commit/9282cadad8fcb28e8ed5ac31c804ff04d7d6e272))
* Introduction crowds infobox ([8c6c3e](https://github.com/itinerare/Mundialis/commit/8c6c3e1ad293de539fdb8fc116a3cebd90eab28b))
* Link parsing error ([142392](https://github.com/itinerare/Mundialis/commit/14239233a77b1e1b0c1ae06c247d52b81d7d1438))
* Navbox displays deleted pages ([251163](https://github.com/itinerare/Mundialis/commit/2511635fa9d858825a365025cf0ebe2433adf3f5))
* Navbox doesn't display subcat pages... ([8329fb](https://github.com/itinerare/Mundialis/commit/8329fb11fe7f7c8dec1d72011c2b5701cc3f06df))
* Parsed links don't display in infobox ([a59195](https://github.com/itinerare/Mundialis/commit/a59195ea51eff9c16ee3bded1ce04827faecb8eb))
* Random page errors when no pages ([a4056d](https://github.com/itinerare/Mundialis/commit/a4056d5b13f4a63045dd5b51de33352d154a59ce))
* Routing error ([893f3a](https://github.com/itinerare/Mundialis/commit/893f3a43f30f52ddb571ec9fb2ffe13f17e055af))
* Section length calc doesn't add right ([904a6a](https://github.com/itinerare/Mundialis/commit/904a6a1bcce147ac7f100bc749bf68cc0af58cb1))
* Subject breadcrumbs links outdated ([c86294](https://github.com/itinerare/Mundialis/commit/c862949f09b079afc0bb0be12a84a5704f332faa))

##### People

* Error viewing relationships ([db68ae](https://github.com/itinerare/Mundialis/commit/db68ae25cc34e667163821c537edd23d9867ec89))

---

## [1.0.0-pre1](https://github.com/itinerare/Mundialis/compare/8c3e2c6ef82213b81555484b5694b4ae87dba3c4...1.0.0-pre1) (2021-08-07)


### Features

* Add category summaries and images ([7398cb](https://github.com/itinerare/Mundialis/commit/7398cbc7fd5da1c486ac1ae40f4481e5bd4d8afb))
* Add general update command ([52e4e2](https://github.com/itinerare/Mundialis/commit/52e4e2e0e76c7a17cda9ae0bd0b7d3c3e2bad68e))
* Add infobox/section sorting to templates ([c9e188](https://github.com/itinerare/Mundialis/commit/c9e188f3b66e2fda95f891b1aa2b3445baabf4c3))
* Add page watching/watched pages ([40df1b](https://github.com/itinerare/Mundialis/commit/40df1b541c1060425bb6a65f3eae1e603b760781))
* Add setup command, for convenience ([888075](https://github.com/itinerare/Mundialis/commit/888075a6876e786a90a8e15a0ce320ce52e7188c))
* Add site settings ([5145e2](https://github.com/itinerare/Mundialis/commit/5145e26001167245f90b82e679287a91bf671da9))
* Add subject category creation and editing ([efa86a](https://github.com/itinerare/Mundialis/commit/efa86a475ae5fdd18d444ab9f6caf42764447b22))
* Add subject links to navbar ([fe7958](https://github.com/itinerare/Mundialis/commit/fe79580799e933f2383c388eae1ee9a761826a43))
* Add subject selection to template sections ([55dfad](https://github.com/itinerare/Mundialis/commit/55dfad61cf3a6df20fb79424eb424b9855cc7b7c))
* Add widget selection to template builder ([bc0a17](https://github.com/itinerare/Mundialis/commit/bc0a17f876e792aaefa7a18a58955fabdc956ace))
* Allow sorting new infobox/template sections ([a621fd](https://github.com/itinerare/Mundialis/commit/a621fda905fd463dc84ebaf29e24038aec7c8880))
* Basic admin panel setup ([b83d4a](https://github.com/itinerare/Mundialis/commit/b83d4a97a3a998596fc6d8e73794e5afff66d3a7))
* Basic auth setup and views ([725ed8](https://github.com/itinerare/Mundialis/commit/725ed8b6f512717ed428237da00d9a6bad30c94f))
* Basic site page setup ([b5f8bf](https://github.com/itinerare/Mundialis/commit/b5f8bf5cbf6b2263a1e3fcab51d4055b3fec09cb))
* Clean up widgets ([dfcd60](https://github.com/itinerare/Mundialis/commit/dfcd6086a72295facecb73501b82b1f0b163da4d))
* Clearer instructions around template editing ([3fdd4e](https://github.com/itinerare/Mundialis/commit/3fdd4e19d23e802fe14d8c45dba331d8d845fce1))
* Helper functions ([4073cf](https://github.com/itinerare/Mundialis/commit/4073cf0ccab141d7353dee5938bd39b033db934e))
* Implement basic template editor for subjects ([3f7628](https://github.com/itinerare/Mundialis/commit/3f76287486ab51ac45e8ac900e8072ed4eb4b04f))
* Implement cascading changes for templates ([aaf235](https://github.com/itinerare/Mundialis/commit/aaf235471e01a1aac22f7e580a3450136462843d))
* Implement cascading for category templates ([1fd9a7](https://github.com/itinerare/Mundialis/commit/1fd9a7479e45b02b44158cff70a377fbde108f8b))
* Implement category sorting ([6740c4](https://github.com/itinerare/Mundialis/commit/6740c4a64c9f6279c28d6a075b6469df237fad2e))
* Implement more basic perms checks and route setup ([d64c25](https://github.com/itinerare/Mundialis/commit/d64c25474f64efc566649ceb57db5ed4ba0106b6))
* Implement rank editing ([90d9f9](https://github.com/itinerare/Mundialis/commit/90d9f9357156e9b9ce190ab3751afdda650509be))
* Navbar, dashboard updates ([356e5e](https://github.com/itinerare/Mundialis/commit/356e5e20c81b346ae8ba44fa88689566a5f4eb2c))
* Replace placeholder images with solid colour ([0a15d3](https://github.com/itinerare/Mundialis/commit/0a15d30d15e60b992c7d8e18e30ce392af6018a9))
* Start of admin template panels/routes ([65a32f](https://github.com/itinerare/Mundialis/commit/65a32f6202e4ea2416bf3f74b057e73f96fd8fe5))
* Update subjects config, rename subject view ([6dd20b](https://github.com/itinerare/Mundialis/commit/6dd20b8ced15cdbfba7acb18c100ad58a55556cb))

##### Auth

* Add admin user edits, email functions ([7aae88](https://github.com/itinerare/Mundialis/commit/7aae885325cf7d7405a92903006de45afc8e764b))
* Add page delete notification ([7d3241](https://github.com/itinerare/Mundialis/commit/7d32411ae567bb56538fb8b12169475fd27f93da))
* Implement user profiles ([3f2eb0](https://github.com/itinerare/Mundialis/commit/3f2eb0e357b2725949838b6c1f8b3c8d861c4b2f))
* Implement user settings ([092fb6](https://github.com/itinerare/Mundialis/commit/092fb65ca06efe2f888dde2ccf4e7ac414dc34f6))
* Invitation code system ([b052d3](https://github.com/itinerare/Mundialis/commit/b052d30edd10267d28acc2d222c4f38a693e1d0f))
* Notification system ([3eeaab](https://github.com/itinerare/Mundialis/commit/3eeaabeb8b0adba792dd6f7e658b6e4072bd9447))

##### Lang

* Add category settings population ([e208b5](https://github.com/itinerare/Mundialis/commit/e208b51d67e661d9520f5062f027aab427cb766e))
* Add etymology and display to entries ([5fa464](https://github.com/itinerare/Mundialis/commit/5fa46477615d81eeb51b281429d22cd0d68e22a2))
* Add lexicon settings and categories ([f4c806](https://github.com/itinerare/Mundialis/commit/f4c806456678ac739a04a89e2f236372b2f7a46b))
* Add page linking in definitions ([4123ee](https://github.com/itinerare/Mundialis/commit/4123eef5a562f48cf8cdc8a28e7f1023191b6c98))
* Add recursive entry descendant listing ([d3ca77](https://github.com/itinerare/Mundialis/commit/d3ca775f397525675d53e4b4112b3e9e2f4e8a74))
* Add sort by meaning to entry index ([ac6b05](https://github.com/itinerare/Mundialis/commit/ac6b053b676f0eafd3b5cf7043fe858800a4f310))
* Easier editing of auto-conj rules ([1bedd5](https://github.com/itinerare/Mundialis/commit/1bedd5dc1d531d6187996160ad42b4f19073873c))
* Entry conjugation/declension, display ([0c25cb](https://github.com/itinerare/Mundialis/commit/0c25cbb3548007406b8fc4cfa5380832c23f0003))
* Finish adding category delete and sort ([f191f9](https://github.com/itinerare/Mundialis/commit/f191f920444cb3765e6b8571079bd491866dff73))
* Lexicon entry display and creation ([fb783c](https://github.com/itinerare/Mundialis/commit/fb783cdb71e6a0f6ee9b55bd3d3d291a97d64e57))
* Make conj/decl collapsible ([be4243](https://github.com/itinerare/Mundialis/commit/be4243c4c2e02ffafa72f45142717c1889cdf8d7))
* Sort entry descendants ([7c45eb](https://github.com/itinerare/Mundialis/commit/7c45eb3fc0dea53f287e6e451de07b6b6765b2a7))

##### Pages

* Add ability to filter by tag(s) ([9dc371](https://github.com/itinerare/Mundialis/commit/9dc371cb2592ae5b36821a33720bf34bd4967c22))
* Add advanced tags + navboxes ([ddb8c9](https://github.com/itinerare/Mundialis/commit/ddb8c905f20333dcdb2847beed18ca10d39016e9))
* Add auth-dependent visibility ([7fe483](https://github.com/itinerare/Mundialis/commit/7fe4837abaaeaf46ca6b13a4176b8b7b9e1f4a5f))
* Add basic page creation and editing ([6befee](https://github.com/itinerare/Mundialis/commit/6befeea7cde6a6d4a39a16ab1241dfc28f57792e))
* Add basic page display ([94850e](https://github.com/itinerare/Mundialis/commit/94850e288bd450eb07395efc9997ed7749ae0841))
* Add basic search/sort to page indexes ([6443d0](https://github.com/itinerare/Mundialis/commit/6443d081516fc34eef5674dd33ac4e370fe0930e))
* Add disambiguation features ([732140](https://github.com/itinerare/Mundialis/commit/7321408cdb280d281df2fad79b9ee1b366b7952e))
* Add list of recent page/image changes ([27a853](https://github.com/itinerare/Mundialis/commit/27a853328017ca43005a788d75a4edbc5e5ab8f3))
* Add optgroups to image page selection ([4686b7](https://github.com/itinerare/Mundialis/commit/4686b76684af34064cf55c3cc45bb4f4bb2f7439))
* Add page image system ([f68807](https://github.com/itinerare/Mundialis/commit/f688076b7607e08bb2b4b2ae87763eff6f810d9b))
* Add page protection ([e5de77](https://github.com/itinerare/Mundialis/commit/e5de77c272be4ee28112303033804429273e29d6))
* Add protected pages special page ([9bfe95](https://github.com/itinerare/Mundialis/commit/9bfe95d3c077c907a817e96f92bd871e6c8328e2))
* Add reason and minor edit to versions ([82cad6](https://github.com/itinerare/Mundialis/commit/82cad6c0e0bbf1264ff3dca1b9e7e02a452451c1))
* Add revision history, image history ([c736b2](https://github.com/itinerare/Mundialis/commit/c736b2e63ef67f0961b27de8548007d19d1821b7))
* Add unwatched special page ([cc98cd](https://github.com/itinerare/Mundialis/commit/cc98cdb18db9979d9ce9ca86aeb971ce5416008c))
* Add user list special page ([9f0239](https://github.com/itinerare/Mundialis/commit/9f023950c26d00340e4364a85ea36a17bf4165bf))
* Add visibility display to page header ([1daea5](https://github.com/itinerare/Mundialis/commit/1daea5b0751f9c5f37240c7d088e9e70d2aa9379))
* Add wanted pages, what links here ([fe8ebc](https://github.com/itinerare/Mundialis/commit/fe8ebc9d5617dad16ffd81cf8b7739ed9afaf7cb))
* Add wiki-style link parsing ([38bd23](https://github.com/itinerare/Mundialis/commit/38bd2352d25506eca33aba4bebd9cdcdcd6a365c))
* All pages special page ([2fcc70](https://github.com/itinerare/Mundialis/commit/2fcc7015eace2a86afc2efc99e88007095926ad9))
* All tags list, all images list ([abcbe6](https://github.com/itinerare/Mundialis/commit/abcbe60ac318cc0cde593bfcdc8651e6d9c2b0f5))
* Auto table of contents ([28ee51](https://github.com/itinerare/Mundialis/commit/28ee517ca867fb4e46d828a5f32df63d690e0b22))
* Better org of category/page indices ([950f9b](https://github.com/itinerare/Mundialis/commit/950f9b109a1e6bce9bcfd6abd44ddc7ccf549703))
* Flow for creating wanted pages ([d4fdc1](https://github.com/itinerare/Mundialis/commit/d4fdc1c0b0ca85d2ce9f93772a0a53e0585c9e20))
* Implement page tags ([457b1e](https://github.com/itinerare/Mundialis/commit/457b1e54b1eab6b6c656a33241915838c70c2128))
* Implement specialized subject fields ([94a7d9](https://github.com/itinerare/Mundialis/commit/94a7d92f91f324ed019829a744b25da6267e57bc))
* Implement updates on wanted page creation ([0a230a](https://github.com/itinerare/Mundialis/commit/0a230a87ae696837a0f026828f46b60e6ce473ed))
* Implement utility tags ([d99eee](https://github.com/itinerare/Mundialis/commit/d99eeeefc3b9fedce9a8f299c1313d0e46c40288))
* Make sections collapseable ([548ab1](https://github.com/itinerare/Mundialis/commit/548ab1fcb407f05b1369ec026a2b8fa4f19cc321))
* More precise people age calculation ([5aede2](https://github.com/itinerare/Mundialis/commit/5aede2b8e3716c1c42119ff7170d95406f64f6e8))
* Most/least revised pages list ([994c96](https://github.com/itinerare/Mundialis/commit/994c96afec9c74e06b1059b4a7c812113f724ac0))
* Most linked-to pages special page ([d5fa0c](https://github.com/itinerare/Mundialis/commit/d5fa0cb5e7071b75c933344c790bbb0fba042fd5))
* Most tagged/untagged special pages ([f34a93](https://github.com/itinerare/Mundialis/commit/f34a93a764e97315b68d3139f38b7423e06a7d8e))
* Page links display summary on hover ([66b003](https://github.com/itinerare/Mundialis/commit/66b003439734d33c2a09cf18d4bd13c460732e30))
* Page moving ([9ccfe7](https://github.com/itinerare/Mundialis/commit/9ccfe745fd0c2ce945807e28be2c32a93861b652))
* Subject and category index views ([5fc5e0](https://github.com/itinerare/Mundialis/commit/5fc5e0ee838578fcf63e9c2de921f6de8676459a))

##### People

* Add basic relationship tracking ([ee2c63](https://github.com/itinerare/Mundialis/commit/ee2c6381baa7319ab3f170c3a09c91372ac9cae5))
* Adjust ancestry styling ([20f141](https://github.com/itinerare/Mundialis/commit/20f1412490c1b50a35c061be8f8508cf8e79cb40))
* Basic family tree/listing page ([eb0ab9](https://github.com/itinerare/Mundialis/commit/eb0ab97b2b4865c5cd2eb2ed46f5b3b2f0d455db))

##### Resources

* Add a bunch of basic resources ([bd2eeb](https://github.com/itinerare/Mundialis/commit/bd2eeb2f8dfd4e11377aa9cb4f38d58a97e5fea2))
* Add command to generate changelog ([b4e2b8](https://github.com/itinerare/Mundialis/commit/b4e2b80b22bb269965bd4ceb620d27b5658ea821))
* Add lightbox UI images ([017ee3](https://github.com/itinerare/Mundialis/commit/017ee3ba1fc5405d0a3652d627a8b9fed72c49d5))
* Add settings config ([9ee9d3](https://github.com/itinerare/Mundialis/commit/9ee9d324d4bb690c4a8f8bc6e7a64803f184986f))
* Track composer.lock ([c79b77](https://github.com/itinerare/Mundialis/commit/c79b778ba108d78f051ca8aa99c006b911fc987e))

##### Time

* Add timeline display ([619fa5](https://github.com/itinerare/Mundialis/commit/619fa57e724532e6aae7028143c94c16489f9cb1))
* Add 'use for dates' check to time divisions ([eda3bd](https://github.com/itinerare/Mundialis/commit/eda3bd29f236e451731228a0f4804a97c8ee9319))
* Chronologies display ([9d54d5](https://github.com/itinerare/Mundialis/commit/9d54d50fdc5b595953e81402dee78f9c92a71c51))
* Implement chronology & time divisions ([fd5caf](https://github.com/itinerare/Mundialis/commit/fd5cafa33061912d800112754aa3f4c331df8b52))

### Bug Fixes

* Admin user routes clobber rank/invite ones ([eed999](https://github.com/itinerare/Mundialis/commit/eed999f329872cdfa1d25ba638c43f75628e9754))
* Cascading field/widget template changes error ([0e7337](https://github.com/itinerare/Mundialis/commit/0e73377344da24abc3b1fb58cc39b050eaddb16c))
* Concepts typo ([b7d998](https://github.com/itinerare/Mundialis/commit/b7d99828d150dc147f432c94464fbe4e0d2452ef))
* Error editing subject categories ([b35834](https://github.com/itinerare/Mundialis/commit/b3583453a9e39db22956e208bdf575298392e07d))
* Issue detecting field changes as removals ([47abc8](https://github.com/itinerare/Mundialis/commit/47abc871203cb90ac8f7bc2c2d0ead3fef0c0cc4))
* Logo present in mobile sidebar ([a199cc](https://github.com/itinerare/Mundialis/commit/a199cc6ed5e391eed9aaefdcd5312c0d0d292119))
* Minor template builder issues ([7fe693](https://github.com/itinerare/Mundialis/commit/7fe6937a102e704ba3c6bbd19f05116637497de1))
* Removing template section removes all of them ([78ad4a](https://github.com/itinerare/Mundialis/commit/78ad4a6110e8db9e9d410a4d47ca97acc0da814f))
* Setup-admin reset out of order ([485b2a](https://github.com/itinerare/Mundialis/commit/485b2af675edb0a2bb406a26494cad51cadca39f))
* TinyMCE won't initialize ([c2370a](https://github.com/itinerare/Mundialis/commit/c2370aa4fb0ef2538f3493f5ae35440509eb2a87))
* Typo in subjects config file ([0d7fa2](https://github.com/itinerare/Mundialis/commit/0d7fa2527a25716155025ac055fef64dbf88434b))
* Update command not declared properly ([90beda](https://github.com/itinerare/Mundialis/commit/90beda5868c93664505cee6106ac780e9c8bca54))
* Validation errors do not appear ([30330c](https://github.com/itinerare/Mundialis/commit/30330c4946afc24c0a0319f0fba542bc981bed3c))

##### Lang

* Add missing auth check to entry index ([d2baa2](https://github.com/itinerare/Mundialis/commit/d2baa277cd67c8b25d88e1779b0902e6306c4e4e))
* Can't edit categories w/o properties ([7f09cc](https://github.com/itinerare/Mundialis/commit/7f09ccbe2af4dc292fe9a8b02849850274dc4506))
* Category conj/declen order inconsistent ([3279f1](https://github.com/itinerare/Mundialis/commit/3279f156d95ddc0b34e7786a5729efce6f868196))
* Category deletion uses wrong function ([0e2583](https://github.com/itinerare/Mundialis/commit/0e2583658f74d1da932d4523f203ae83a8df772d))
* Category edit button links to wrong page ([b3ecc6](https://github.com/itinerare/Mundialis/commit/b3ecc6fabffa0ad4af4ac19f75b856a7ff7651b5))
* Category table display wonky ([603464](https://github.com/itinerare/Mundialis/commit/603464d222f2306035bebbff4c500beec7012f7d))
* Creation of empty etymology records ([aa6e42](https://github.com/itinerare/Mundialis/commit/aa6e42f8dcddbbad9f22af381229f7edd667ae31))

##### Pages

* Auto-ToC width wonky on mobile ([c629f8](https://github.com/itinerare/Mundialis/commit/c629f88362d61173ca7fd70a83c46aef402217bc))
* Cannot remove extra links from images ([7d031e](https://github.com/itinerare/Mundialis/commit/7d031e747232b1435855c1f83e67bf9549a15f23))
* Cannot set page non-visible ([578712](https://github.com/itinerare/Mundialis/commit/57871216fc47757ae8da22fc382ed2b5302909f0))
* Category optgroups sorted wrong ([35e1ed](https://github.com/itinerare/Mundialis/commit/35e1ed905c03b7e532831f2c537db2786d56bd63))
* Check image has pages when restoring ([c91f53](https://github.com/itinerare/Mundialis/commit/c91f53632a8245fd0052813b3e306784bf17cb35))
* Data column not nullable ([b7b0ed](https://github.com/itinerare/Mundialis/commit/b7b0ed9b4159cc1f4caa711a8fb0cb781390219e))
* Edit/reset allow deleted page title ([aec9de](https://github.com/itinerare/Mundialis/commit/aec9de61db75f8138e66349f9e2269ab608324c4))
* Error fetching page data from version ([3e6ccb](https://github.com/itinerare/Mundialis/commit/3e6ccb26d0f3517797f04a45609dbebe36f8ff85))
* Error viewing categories ([878c97](https://github.com/itinerare/Mundialis/commit/878c97f681eac8d140dd0d9a330655b673c16991))
* Force-deleted pages error changes list ([98f958](https://github.com/itinerare/Mundialis/commit/98f95886d6b1a3e2f59c28fa8a3ea759dced6279))
* Gallery formatting slightly wonky ([1153b4](https://github.com/itinerare/Mundialis/commit/1153b4c9ef608a94e8fb5c07e03301eff5a16987))
* Hidden pages visible in navbox ([52b7db](https://github.com/itinerare/Mundialis/commit/52b7db8580b1f3bdb413732a83640edfbd206634))
* Image files aren't deleted properly ([f7380d](https://github.com/itinerare/Mundialis/commit/f7380d6aa6be16884f54ea6a001a9da6ebc89fc5))
* Image revision log takes up more space ([e45865](https://github.com/itinerare/Mundialis/commit/e458651c373beee3412602f8448f37799d75157e))
* Image unlink deletes page not detaches ([d3ae71](https://github.com/itinerare/Mundialis/commit/d3ae71900e32311387ad8d81c34f13f8ba73feab))
* Link parse detection issue, dupe links ([72c475](https://github.com/itinerare/Mundialis/commit/72c4751a8d1118ce4c272acd7484494d1f45b1d1))
* Link parse issues for people, disambig ([ab7d77](https://github.com/itinerare/Mundialis/commit/ab7d77b2275c79573bd3eab1833a84c816160ac3))
* More consistent img creator processing ([4afabc](https://github.com/itinerare/Mundialis/commit/4afabc8b80c72de39d1b7a30fba3957ed8cc6ba9))
* Navbox errors when no context pages ([486c47](https://github.com/itinerare/Mundialis/commit/486c4718f01da90c02cb114892c21b3613a112ff))
* Page index top pagination out of order ([9cf99b](https://github.com/itinerare/Mundialis/commit/9cf99b96d51a620482199b5e92079f909c2d9e31))
* Page link detection broken ([2f1e45](https://github.com/itinerare/Mundialis/commit/2f1e45a8c777dd526be7bda3521fdb8c99080957))
* Page list cols try to nest ([a9d334](https://github.com/itinerare/Mundialis/commit/a9d3344243cbd84e44ec738fb33b8ec2274d62c2))
* Parent place field populated wrong ([511604](https://github.com/itinerare/Mundialis/commit/51160477defeea2b3c8d6c79396ad600e275aeec))
* Subject query inexplicably hardcoded ([b8caa1](https://github.com/itinerare/Mundialis/commit/b8caa1cf938e7278c69909ecd93b6903fcd5ecb1))
* Subject routes not adjusted ([835e57](https://github.com/itinerare/Mundialis/commit/835e57f39782871006f1a5baa21aae9ea8dd48f8))
* Tag field not labeled optional ([d0b508](https://github.com/itinerare/Mundialis/commit/d0b5087ff68673a99ea8c58e1af01be3196122b7))
* Version data displaying incorrectly ([532103](https://github.com/itinerare/Mundialis/commit/532103cccbbb3c807d96c7ed602ceb0e4da193db))
* Wiki link parsing backwards ([751804](https://github.com/itinerare/Mundialis/commit/751804f6670f913265f368018595c0a0d446412f))

##### People

* Incorrect form order when creating relationships ([6d1b48](https://github.com/itinerare/Mundialis/commit/6d1b48a3775078ebd950e7975b0753b272d3512b))

##### Resources

* Changelog command has wrong description ([ff1859](https://github.com/itinerare/Mundialis/commit/ff1859528074ce1d44be37ea75e3ea725890a803))

##### Time

* Chronology editing points at category editing ([20b89f](https://github.com/itinerare/Mundialis/commit/20b89f4f954b3bdc27cdfe75f3c8841b15dcb1a0))
* Chronology index table displays wonky ([086068](https://github.com/itinerare/Mundialis/commit/086068a5aada957adb6078f1eb9c485339ddd6f0))
* Error sorting chronologies ([541150](https://github.com/itinerare/Mundialis/commit/54115014b2f62220236bb463e28fe9455906d04d))
* Fix error editing divisions ([68ef60](https://github.com/itinerare/Mundialis/commit/68ef605bab10629347d4778ff32e8f1d437e0507))
* Renaming time divisions would delete/recreate ([98760d](https://github.com/itinerare/Mundialis/commit/98760d898ea0f25d38dbf58c6aa3903b4623e85a))

---

