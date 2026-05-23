#!/usr/bin/env node
// webpackbar 5.0.2 (a transitive dep of laravel-mix 6) extends webpack's
// ProgressPlugin and clobbers `this.options` with webpackbar-specific keys
// (name, color, reporter, reporters). Newer webpack 5.x validates `this.options`
// against ProgressPlugin's schema (additionalProperties:false) and fails.
// This patch renames webpackbar's own option storage to `this._opts` so the
// inherited `this.options = { activeModules: true }` stays intact for the
// schema check.
const fs = require('fs');
const { execSync } = require('child_process');

const replacements = [
  ['this.options = Object.assign({}, DEFAULTS, options)', 'this._opts = Object.assign({}, DEFAULTS, options)'],
  ['this.options.reporters', 'this._opts.reporters'],
  ['this.options.reporter', 'this._opts.reporter'],
  ['this.options[reporter]', 'this._opts[reporter]'],
  ['this.options.name', 'this._opts.name'],
  ['this.options.color', 'this._opts.color'],
];

const output = execSync(
  'find node_modules -path "*/webpackbar/dist/index.*js" \\( -name "index.cjs" -o -name "index.mjs" \\)',
  { encoding: 'utf8' }
);

output.trim().split('\n').filter(Boolean).forEach(file => {
  try {
    let src = fs.readFileSync(file, 'utf8');
    if (src.includes('this._opts = Object.assign({}, DEFAULTS, options)')) {
      return; // already patched
    }
    let changed = false;
    for (const [from, to] of replacements) {
      if (src.includes(from)) {
        src = src.split(from).join(to);
        changed = true;
      }
    }
    if (changed) {
      fs.writeFileSync(file, src);
      console.log('Patched:', file);
    }
  } catch (_) {}
});
