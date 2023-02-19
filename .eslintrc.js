module.exports = {
  root: true,
  env: {
    node: true,
  },
  'extends': [
    'plugin:vue/vue3-essential',
    'eslint:recommended',
    '@vue/typescript/recommended',
  ],
  parserOptions: {
    ecmaVersion: 2020,
  },
  rules: {
    'no-console': 'warn',
    'no-debugger': 'warn',
    semi: 'off',
    'comma-dangle': ['warn', 'always-multiline'],
    'no-multiple-empty-lines': 'off',
    quotes: ['error', 'single', { avoidEscape: true }],
    'import/prefer-default-export': 'off',
    'import/extensions': 'off',
    '@typescript-eslint/no-empty-function': 'off',
    '@typescript-eslint/no-explicit-any': 'off',
    'vue/multi-word-component-names': 'off',
    'vue/attribute-hyphenation': 'warn',
    'vue/attributes-order': 'warn',
    'vue/max-attributes-per-line': 'off',
    'vue/no-confusing-v-for-v-if': 'error',
    'vue/order-in-components': 'warn',
    'vue/prop-name-casing': 'warn',
    'vue/no-dupe-keys': ['error', {
      groups: [],
    }],
  },
}
