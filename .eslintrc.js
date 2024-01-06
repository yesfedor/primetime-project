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
    semi: ['error', 'never'],
    'comma-dangle': ['warn', 'always-multiline'],
    'no-multiple-empty-lines': 'off',
    quotes: ['error', 'single', { avoidEscape: true }],
    'prefer-const': 'off',
    'import/prefer-default-export': 'off',
    'import/extensions': 'off',
    '@typescript-eslint/no-empty-function': 'off',
    '@typescript-eslint/no-explicit-any': 'off',
    'vue/multi-word-component-names': 'off',
    'vue/attribute-hyphenation': 'warn',
    'vue/attributes-order': 'warn',
    'vue/max-attributes-per-line': 'off',
    'vue/order-in-components': 'warn',
    'vue/prop-name-casing': 'warn',
    'vue/no-dupe-keys': ['error', {
      groups: [],
    }],
    '@typescript-eslint/no-unused-vars': 'off',
    'vue/no-ref-as-operand': 'off',
  },
}
