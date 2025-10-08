#!/usr/bin/env node
import fs from 'fs';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';
import { execSync } from 'child_process';
import readline from 'readline';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// Create readline interface
const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

const question = (query) => new Promise((resolve) => rl.question(query, resolve));

// Colors for console output
const colors = {
  reset: '\x1b[0m',
  red: '\x1b[31m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
  magenta: '\x1b[35m',
  cyan: '\x1b[36m',
};

function log(message, color = 'reset') {
  console.log(`${colors[color]}${message}${colors.reset}`);
}

// Check if working tree is clean
function checkWorkingTree() {
  try {
    const status = execSync('git status --porcelain').toString();
    if (status) {
      log('\n❌ Error: Your repository working tree is dirty.', 'red');
      log('This operation needs to be run on a clean working tree.', 'red');
      log('Commit or stash your changes before proceeding.\n', 'red');
      process.exit(1);
    }
    log('✅ Git working tree is clean', 'green');
  } catch (error) {
    log('❌ Error checking git status', 'red');
    process.exit(1);
  }
}

// Run composer check
function runComposerCheck() {
  try {
    log('\n🔍 Running composer check (lint, analyze)...', 'cyan');
    execSync('composer check', { stdio: 'inherit' });
    log('✅ Composer check passed', 'green');
  } catch (error) {
    log('\n❌ Composer check failed', 'red');
    log('Fix the errors above before publishing', 'yellow');
    process.exit(1);
  }
}


// Parse current version from PHP file
function getCurrentVersion() {
  const phpFilePath = join(__dirname, '..', 'pilgrimz-tour-map.php');
  const phpContent = fs.readFileSync(phpFilePath, 'utf8');

  // Match "Version: X.Y.Z" in the plugin header
  const versionMatch = phpContent.match(/\* Version:\s*(\d+\.\d+\.\d+)/);

  if (!versionMatch) {
    log('❌ Error: Could not find version in pilgrimz-tour-map.php', 'red');
    process.exit(1);
  }

  return versionMatch[1];
}

// Parse current version and calculate options
function getVersionOptions(currentVersion) {
  const [major, minor, patch] = currentVersion.split('.').map(Number);

  return {
    patch: `${major}.${minor}.${patch + 1}`,
    minor: `${major}.${minor + 1}.0`,
    major: `${major + 1}.0.0`,
  };
}

// Prompt user for version type
async function promptVersionType() {
  const currentVersion = getCurrentVersion();
  const versions = getVersionOptions(currentVersion);

  log(`\n📦 Current version: ${currentVersion}`, 'cyan');
  log('\nSelect version update type:', 'yellow');
  log(`1. Patch (${versions.patch}) - Bug fixes and minor changes`);
  log(`2. Minor (${versions.minor}) - New features, backwards compatible`);
  log(`3. Major (${versions.major}) - Breaking changes`);
  log('4. Cancel');

  const answer = await question('\nEnter your choice (1-4): ');

  switch (answer.trim()) {
    case '1':
      return { type: 'patch', version: versions.patch };
    case '2':
      return { type: 'minor', version: versions.minor };
    case '3':
      return { type: 'major', version: versions.major };
    case '4':
      log('\n❌ Operation cancelled', 'yellow');
      process.exit(0);
    default:
      log('Invalid choice. Please try again.', 'red');
      return promptVersionType();
  }
}

// Update version in PHP file (two places: header and constant)
function updatePhpVersion(newVersion) {
  try {
    const phpFilePath = join(__dirname, '..', 'pilgrimz-tour-map.php');
    let phpContent = fs.readFileSync(phpFilePath, 'utf8');

    // Update "Version: X.Y.Z" in plugin header
    phpContent = phpContent.replace(
      /(\* Version:\s*)(\d+\.\d+\.\d+)/,
      `$1${newVersion}`
    );

    // Update PILGRIMZ_TOUR_MAP_VERSION constant
    phpContent = phpContent.replace(
      /(define\(\s*'PILGRIMZ_TOUR_MAP_VERSION',\s*')(\d+\.\d+\.\d+)('\s*\);)/,
      `$1${newVersion}$3`
    );

    fs.writeFileSync(phpFilePath, phpContent);
    log(`✅ Updated pilgrimz-tour-map.php to version ${newVersion}`, 'green');
  } catch (error) {
    log('❌ Error updating pilgrimz-tour-map.php', 'red');
    console.error(error);
    process.exit(1);
  }
}

// Commit version changes
function commitVersionChange(version) {
  try {
    execSync('git add pilgrimz-tour-map.php');
    execSync(`git commit -m "chore: publish version ${version}"`);
    log(`✅ Committed version ${version}`, 'green');
  } catch (error) {
    log('❌ Error committing changes', 'red');
    process.exit(1);
  }
}

// Create and push git tag
function createGitTag(version) {
  try {
    execSync(`git tag v${version}`);
    log(`✅ Created tag: v${version}`, 'green');
  } catch (error) {
    log('❌ Error creating git tag', 'red');
    process.exit(1);
  }
}

// Push commits and tags
function pushToGit() {
  try {
    execSync('git push');
    execSync('git push --tags');
    log('✅ Pushed commits and tags to remote', 'green');
  } catch (error) {
    log('❌ Error pushing to git', 'red');
    process.exit(1);
  }
}


// Confirm action with user
async function confirmAction(message) {
  const answer = await question(`${message} (y/n): `);
  return answer.trim().toLowerCase() === 'y';
}

// Main publish workflow
async function publish() {
  try {
    log('\n🚀 WordPress Plugin Publishing Workflow', 'magenta');
    log('==========================================\n', 'magenta');

    // Step 1: Check git working tree
    log('Step 1: Checking git status...', 'blue');
    checkWorkingTree();

    // Step 2: Run composer check
    log('\nStep 2: Running composer check...', 'blue');
    runComposerCheck();

    // Step 3: Choose version
    log('\nStep 3: Version selection', 'blue');
    const { type, version } = await promptVersionType();

    // Step 4: Confirm
    log(`\n📋 Summary:`, 'yellow');
    log(`   Version type: ${type}`);
    log(`   New version: ${version}`);
    log(`   Will commit, tag, and push to GitHub`);
    log(`   GitHub Actions will automatically create the release\n`);

    const confirmed = await confirmAction('Proceed with publishing?');
    if (!confirmed) {
      log('\n❌ Operation cancelled', 'yellow');
      process.exit(0);
    }

    // Step 5: Update version
    log('\nStep 4: Updating version...', 'blue');
    updatePhpVersion(version);

    // Step 6: Commit changes
    log('\nStep 5: Committing changes...', 'blue');
    commitVersionChange(version);

    // Step 7: Create tag
    log('\nStep 6: Creating git tag...', 'blue');
    createGitTag(version);

    // Step 8: Push to git
    log('\nStep 7: Pushing to git...', 'blue');
    pushToGit();

    // Success!
    log('\n✨ Success! ✨', 'green');
    log('==========================================', 'green');
    log(`📦 Version ${version} has been published`, 'green');
    log(`🏷️  Tag v${version} created and pushed`, 'green');
    log(`🚀 GitHub Actions will create the release automatically`, 'green');
    log(`\nView releases: https://github.com/pilgrimz-dev/pilgrimz-tour-map-wp/releases\n`, 'cyan');
  } catch (error) {
    log(`\n❌ Unexpected error: ${error.message}`, 'red');
    process.exit(1);
  } finally {
    rl.close();
  }
}

// Run the publish workflow
publish();
