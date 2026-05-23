#!/usr/bin/env node
// Node 22+ changed how files with "type":"module" are handled when required via exports map.
// The yargs package ships an extensionless CJS shim (yargs/yargs) but marks the package
// as "type":"module", causing require() to fail in Node 22+. This removes "type":"module"
// from all yargs installations so the CJS shim loads correctly.
const fs = require('fs');
const { execSync } = require('child_process');

const output = execSync(
  'find node_modules -name "package.json" -path "*/yargs/package.json"',
  { encoding: 'utf8' }
);

output.trim().split('\n').filter(Boolean).forEach(file => {
  try {
    const pkg = JSON.parse(fs.readFileSync(file, 'utf8'));
    if (pkg.type === 'module') {
      delete pkg.type;
      fs.writeFileSync(file, JSON.stringify(pkg, null, 2) + '\n');
      console.log('Patched:', file);
    }
  } catch (_) {}
});
