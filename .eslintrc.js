module.exports = {
  env: {
    'es2021': true,
    'browser': true,
    'node': true,
    'vue/setup-compiler-macros': true,
  },
  extends: [
    // 'eslint:recommended',
    'plugin:vue/vue3-recommended',
    'prettier',
    '@vue/typescript/recommended',
  ],
  parserOptions: {
    ecmaVersion: 2021,
  },
  rules: {
    // override/add rules settings here, such as:
    // 'vue/no-unused-vars': 'error'
    'vue/html-self-closing': [
      'error',
      {
        html: {
          void: 'always',
          normal: 'always',
          component: 'always',
        },
      },
    ],
    'vue/html-closing-bracket-spacing': [
      'error',
      {
        selfClosingTag: 'always',
      },
    ],
  },
  globals: {
    route: true,
  },
}
