# Selectize runtime

This directory contains the prebuilt Selectize 0.15.2 JavaScript bundle from
[`@selectize/selectize`](https://www.npmjs.com/package/@selectize/selectize/v/0.15.2),
corresponding to the upstream
[`v0.15.2` source](https://github.com/selectize/selectize.js/tree/v0.15.2).

The package modifies label lookup to compare DOM properties, replaces direct
own-property calls and uses collision-safe option, group, rendering and search
dictionaries. It also synchronizes removed option values, parses JSON option
metadata and cleans up event listeners. Unknown-handler removal is a no-op, and
instance event namespaces are never reused. Option metadata must be a JSON
object; HTML values remain the submitted identifiers. Submitted values are
preserved. Formatting follows this repository. The npm dependency still supplies
the Bootstrap 5 stylesheet; `SelectizeRuntimeAsset` publishes this compatible
runtime.

The npm `@selectize/selectize@0.15.2` file `dist/js/selectize.js` SHA-256 is:

```text
b5563072b910123b98b870f206cd86fb1c3a8b12534cfbeccec97b061fc903d4
```

Option, group, user-option, rendering and search maps have no prototype; custom
plugins must use safe own-property checks, as described in
[upgrade guide](../../../../UPGRADE.md).

The original copyright and license notices remain in `selectize.js`. Selectize,
MicroPlugin and Sifter are distributed under the Apache License 2.0 in
[LICENSE](LICENSE). The included highlight v3 code retains its MIT notice for
Johann Burkard and the modification notices for Marshal and Brian Reavis. Its
MIT terms are reproduced below. They also apply to the included
[MicroEvent](https://github.com/jeromeetienne/microevent.js/blob/master/MIT-LICENSE.txt)
code: Copyright (c) 2011 Jerome Etienne, <http://jetienne.com>.

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
