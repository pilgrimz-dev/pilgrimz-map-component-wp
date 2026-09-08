# AGENTS.md

This repository keeps canonical AI operating guidance in `CLAUDE.md` and knowledge in `.claude/skills/SKILL.md` when present.

## Source of Truth
- Project instructions: `CLAUDE.md`
- Skills index: `.claude/skills/SKILL.md` (if this project defines skills)
- Commands: `.claude/commands/` (if this project defines commands)
- MCP servers: `.mcp.json` (if present)

## Shared Project Memory
- Follow the project-memory policy in `CLAUDE.md`; `docs/memory/` is the shared memory store for Claude and Codex.
- At session start and after context compaction, read `docs/memory/README.md` when present, then read the memory files relevant to the task.
- Before working in a subproject, read its `CLAUDE.md` and its `docs/memory/README.md` when present, then load relevant memories.
- Write new `project` and `reference` memories in the owning project's `docs/memory/`, using the existing frontmatter and one fact per file. Add a one-line pointer to its `README.md`; update or remove memories that prove incorrect or outdated.
- Keep `user` and `feedback` memories machine-local as required by the canonical policy. Do not create a separate Codex copy of shared project memories.

## Additive Compatibility Policy
- Do not remove, replace, or migrate away from existing Claude setup.
- Keep `CLAUDE.md`, `.claude/skills`, `.claude/commands`, and `.mcp.json` canonical.
- Treat Agent/Cursor files as thin adapters that point to canonical docs.
- Preserve existing symlinks and existing behavior.
