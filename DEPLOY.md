# Deploy Script Guide

Deploys only git-changed files to the Luxora production server via rsync over SSH.

---

## Requirements

- SSH key file at `~/.ssh/id_ed25519_nera` on your local machine
- `rsync` installed (comes pre-installed on macOS)

---

## Basic Usage

Open your terminal in the project root and run:

```bash
bash deploy.sh [mode]
```

### Modes

| Mode | What it deploys | When to use |
|---|---|---|
| `staged` | Files you've run `git add` on | Before committing — deploy work in progress |
| `last-commit` | Files changed in the last commit | After committing — deploy what you just committed (default) |
| `unpushed` | All commits not yet pushed to origin/main | Deploy everything that hasn't gone to GitHub yet |

If you don't specify a mode, it defaults to `last-commit`.

---

## Step-by-step Workflow

### Option A — Deploy after committing (recommended)

```bash
# 1. Make your changes, then commit
git add .
git commit -m "your message"

# 2. Deploy the last commit
bash deploy.sh
# or explicitly:
bash deploy.sh last-commit
```

### Option B — Deploy staged files before committing

```bash
# 1. Stage the files you want to deploy
git add wp-content/themes/nera-competitions-standard/...

# 2. Deploy staged files
bash deploy.sh staged
```

### Option C — Deploy everything not yet on GitHub

```bash
bash deploy.sh unpushed
```

---

## What happens when you run it

1. The script detects changed files from git
2. It prints a list of files to **upload** (`+`) and **delete** (`-`) on the server
3. You confirm with `y` before anything is sent
4. Only those specific files are synced — nothing else is touched on the server

Example output:
```
Files to upload:
  + wp-content/themes/nera-competitions-standard/inc/competition-shortcodes.php
  + wp-content/themes/nera-competitions-standard/template-parts/components/prize-card.php

Upload: 2 file(s) | Delete: 0 file(s)

Proceed? (y/N) y

Uploading files...
Done.
```

---

## Notes

- Existing files on the server are **overwritten** — no backup is made
- Deleted files (removed via git) are **deleted from the server** automatically
- The "Transfer starting: X files" count from rsync includes parent directories — this is normal, only the actual changed files are uploaded
- To abort at any point, type `N` or press `Ctrl+C`
