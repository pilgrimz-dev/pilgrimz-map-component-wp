# AGENTS.md

This repository keeps canonical AI operating guidance in `CLAUDE.md` and knowledge in `.claude/skills/SKILL.md` when present.

## Source of Truth
- Project instructions: `CLAUDE.md`
- Skills index: `.claude/skills/SKILL.md` (if this project defines skills)
- Commands: `.claude/commands/` (if this project defines commands)
- MCP servers: `.mcp.json` (if present)

## Additive Compatibility Policy
- Do not remove, replace, or migrate away from existing Claude setup.
- Keep `CLAUDE.md`, `.claude/skills`, `.claude/commands`, and `.mcp.json` canonical.
- Treat Agent/Cursor files as thin adapters that point to canonical docs.
- Preserve existing symlinks and existing behavior.
