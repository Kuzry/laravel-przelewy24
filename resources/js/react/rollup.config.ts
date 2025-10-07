import terser from '@rollup/plugin-terser';
import typescript from '@rollup/plugin-typescript';
import del from 'rollup-plugin-delete';

const isProd = process.env.NODE_ENV === 'production';

export default {
  input: ['src/index.ts'],
  output: [
    {
      file: './dist/index.js',
      format: 'cjs',
      entryFileNames: '[name].js',
      sourcemap: !isProd,
    },
    {
      file: './dist/index.esm.js',
      format: 'es',
      entryFileNames: '[name].esm.js',
      sourcemap: !isProd,
    },
  ],
  plugins: [del({ targets: 'dist/*' }), typescript(), isProd && terser()],
};
