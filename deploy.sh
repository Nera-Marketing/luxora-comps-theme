#!/bin/bash
# deploy.sh — Deploy only git-changed files to remote server via rsync
#
# Usage:
#   bash deploy.sh [mode]
#
# Modes:
#   staged       — files currently staged (git add'd, not yet committed)
#   last-commit  — files changed in the last commit (default)
#   unpushed     — all commits not yet pushed to origin/main

# ── Config ───────────────────────────────────────────────────────────────────
SSH_USER="u24-3uvvleo8qtnu"
SSH_HOST="c1108063.sgvps.net"
SSH_PORT="18765"
SSH_KEY="~/.ssh/id_ed25519_nera"
REMOTE_PATH="/home/u24-3uvvleo8qtnu/www/luxoradraws.co.uk/public_html"
LOCAL_ROOT="$(git rev-parse --show-toplevel)"
# ─────────────────────────────────────────────────────────────────────────────

MODE="${1:-last-commit}"
SSH_CMD="ssh -p $SSH_PORT -i $SSH_KEY"

case "$MODE" in
  staged)
    CHANGED=$(git diff --cached --name-only --diff-filter=ACM)
    DELETED=$(git diff --cached --name-only --diff-filter=D)
    ;;
  last-commit)
    CHANGED=$(git diff HEAD~1 HEAD --name-only --diff-filter=ACM)
    DELETED=$(git diff HEAD~1 HEAD --name-only --diff-filter=D)
    ;;
  unpushed)
    CHANGED=$(git diff origin/main...HEAD --name-only --diff-filter=ACM)
    DELETED=$(git diff origin/main...HEAD --name-only --diff-filter=D)
    ;;
  *)
    echo "Unknown mode: $MODE"
    echo "Usage: bash deploy.sh [staged|last-commit|unpushed]"
    exit 1
    ;;
esac

# Check if there's anything to do
if [ -z "$CHANGED" ] && [ -z "$DELETED" ]; then
  echo "No changed files detected for mode: $MODE"
  exit 0
fi

# Print summary
if [ -n "$CHANGED" ]; then
  echo "Files to upload:"
  echo "$CHANGED" | sed 's/^/  + /'
fi

if [ -n "$DELETED" ]; then
  echo "Files to delete on remote:"
  echo "$DELETED" | sed 's/^/  - /'
fi

echo ""
CHANGED_COUNT=$([ -n "$CHANGED" ] && echo "$CHANGED" | wc -l | tr -d ' ' || echo 0)
DELETED_COUNT=$([ -n "$DELETED" ] && echo "$DELETED" | wc -l | tr -d ' ' || echo 0)
echo "Upload: $CHANGED_COUNT file(s) | Delete: $DELETED_COUNT file(s)"
echo ""

read -p "Proceed? (y/N) " confirm
[ "$confirm" != "y" ] && echo "Aborted." && exit 0

# Upload changed/added files via rsync
if [ -n "$CHANGED" ]; then
  echo ""
  echo "Uploading files..."
  echo "$CHANGED" | rsync -avz \
    --files-from=- \
    -e "$SSH_CMD" \
    "$LOCAL_ROOT/" \
    "$SSH_USER@$SSH_HOST:$REMOTE_PATH/"
fi

# Delete removed files on remote
if [ -n "$DELETED" ]; then
  echo ""
  echo "Deleting removed files from remote..."
  while IFS= read -r file; do
    echo "  Deleting: $file"
    $SSH_CMD "$SSH_USER@$SSH_HOST" "rm -f \"$REMOTE_PATH/$file\""
  done <<< "$DELETED"
fi

echo ""
echo "Done."
