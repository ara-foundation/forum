#!/usr/bin/env node

// esbuild ./scripts/web3.js --bundle --format=esm --sourcemap --main-fields=browser,module,main --define:process.env.NODE_ENV='production' --inject:./scripts/node-globals.js --splitting --outdir=../assets

const GlobalsPolyfills = require('@esbuild-plugins/node-globals-polyfill').default;
const NodeModulesPolyfills = require('@esbuild-plugins/node-modules-polyfill').default;

require('esbuild')
  .build({
    entryPoints: ['./scripts/web3.js'],
    bundle: true,
    format: 'esm',
    sourcemap: true,
    mainFields: ['browser', 'module', 'main'],
    define: {
      'process.env.NODE_ENV': '"production"',
    },
    // inject: ['./scripts/node-globals.js'],
    splitting: true,
    outdir: '../assets',
    loader: {
      '.svg': 'dataurl',
    },
    plugins: [
      GlobalsPolyfills({
        process: true,
        buffer: true,
        define: {},
      }),
      NodeModulesPolyfills(),
    ],
  })
  .catch(() => process.exit(1));
