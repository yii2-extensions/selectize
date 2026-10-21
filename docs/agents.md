# AI assistance

The [`yii2-selectize` skill](../skills/yii2-selectize/SKILL.md) guides widget configuration, validation, remote data and
lifecycle cleanup using the actual installed package contracts.

## Install in an application

### Automatic Composer installation

Use the [one-time application setup](https://yii2-framework.github.io/docs/project/ai-agents/#composer-installation)
with `auto-sync` set to `true` in `skills.json`, approving `yii2-extensions/selectize` explicitly. The package declares
`skills` as its bundle source. For an installed release containing the skill:

```bash
composer install
composer skills:show
```

With `auto-sync` enabled, `composer install` and `composer update` synchronize the skill automatically. If it is
`false`, run `composer skills:update` after dependency installation or updates.

For a release without the bundle, register a source checkout once instead:

```bash
composer skills:add /absolute/path/to/selectize --skill=yii2-selectize
```

### Optional manual installation

Copy or link the complete `yii2-selectize` directory from the package's `skills/` directory into your client's project
skill path:

| Client      | Skill directory                                                        |
| ----------- | ---------------------------------------------------------------------- |
| Codex       | `.agents/skills/yii2-selectize/`                                       |
| Claude Code | `.claude/skills/yii2-selectize/`                                       |
| OpenCode    | `.opencode/skills/yii2-selectize/` or `.agents/skills/yii2-selectize/` |

Keep `SKILL.md` inside that directory. Invoke `$yii2-selectize` in Codex, `/yii2-selectize` in Claude Code, or load
`yii2-selectize` with OpenCode's skill tool.

A Composer installation under `vendor/` alone does not expose the skill to an agent opened at the application root. Keep
one effective copy per client and refresh it when upgrading the package. See the client discovery rules for
[Claude Code](https://code.claude.com/docs/en/skills) and [OpenCode](https://opencode.ai/docs/skills/).

## Resolve the package revision

In a consuming app, inspect `composer show yii2-extensions/selectize --format=json` and the configured vendor
directory's `composer/installed.php`. Resolve the package's real install path, version and Composer-selected reference;
check the lockfile separately. For a live checkout or path installation, inspect its HEAD and current files. A dirty
working tree does not have a matching release SHA.

The copied skill uses that package root for `docs/`, `src/` and manifests; its own client installation directory is not
the package root. Use hosted pages or source at the selected revision only when provenance matches. If matching guides
are unavailable, use the installed source, PHPDoc and changelog and report the gap.

## Framework conventions

For Yii 22 conventions, use the `yii2-framework` workflow from the
[common skill package](https://github.com/yii2-extensions/skill). The
[one-time Composer application setup](https://yii2-framework.github.io/docs/project/ai-agents/#composer-installation)
installs it together with the other framework skills. The same `auto-sync` setting and manual synchronization step
apply. Keep one framework donor in the application configuration.

For optional manual installation, copy the complete `skills/yii2-framework/` directory from a common-package checkout,
including its referenced resources, into the client project path above. Verify discovery in the client. For Yii 2.0
applications, use matching framework contracts; do not apply Yii 22-only setup automatically.

For an available hosted documentation deployment, fetch its `llms.txt` index before selecting pages. The Yii
deployment's index path is `https://yii2-framework.github.io/docs/llms.txt`; a page such as `guide/start-installation/`
has Markdown at `guide/start-installation/index.md`. Check the source revision rather than relying on the displayed
version label. When hosted material is unavailable or mismatched, use the local fallback above. Installing a skill does
not establish that hosted documentation exists for the installed extension revision.

## Verify use

Start a fresh client session at the application root, inspect its skill list and explicitly invoke `yii2-selectize` for
a small form task. File installation, discovery, instruction loading and working integration are separate checks;
automatic selection depends on the client and model.

Verify form validation, remote loading and cleanup in the consuming application after activating the skill.

[Back to documentation](../README.md#documentation)
