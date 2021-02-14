module.exports = {
  'root': true,
  'overrides': [
    {
      'files': ['src/**/*.ts'],
      'parserOptions': {
        'project': [
          'tsconfig.app.json'
        ]
      },
      'extends': [
        'plugin:@angular-eslint/recommended',

        // AirBnB Styleguide rules
        "eslint:recommended",
        'airbnb-typescript/base',

        "plugin:@typescript-eslint/recommended",
        "plugin:@typescript-eslint/recommended-requiring-type-checking",

        // Settings for Prettier
        'prettier/@typescript-eslint',
        'plugin:prettier/recommended'
      ],
      'rules': {
        '@angular-eslint/directive-selector': ['warn', {'type': 'attribute', 'prefix': ['sp'], 'style': 'camelCase'}],
        '@angular-eslint/component-selector': ['warn', {'type': 'element', 'prefix': ['sp'], 'style': 'kebab-case'}],
        'quotes': ['error', 'single', {'allowTemplateLiterals': true}],
        // Custom rules
        'import/prefer-default-export': 'off',
        'lines-between-class-members': 'off',
        '@typescript-eslint/lines-between-class-members': ['error', 'always', {'exceptAfterSingleLine': true}],
        'class-methods-use-this': 'off',
        '@typescript-eslint/unbound-method': ['error', {ignoreStatic: true}],
        'dot-notation': 'off',
        '@typescript-eslint/dot-notation': ['off'],
        'linebreak-style': ['error', 'unix'],
        'no-param-reassign': ['warn', {'props': false}],
        '@typescript-eslint/no-inferrable-types': 'off',
        'no-plusplus': 'off',
        'no-underscore-dangle': 'off',
        'no-continue': 'off',
        '@angular-eslint/no-input-rename': 'warn',
        '@angular-eslint/no-output-on-prefix': 'off',
        '@typescript-eslint/no-use-before-define': 'warn'
      }
    },

    {
      'files': ['src/**/*.component.html'],
      'extends': [
        'plugin:@angular-eslint/template/recommended',
        'prettier/@typescript-eslint',
        'plugin:prettier/recommended'
      ],
      'rules': {
        'max-len': ['error', {'code': 140}]
      }
    }
  ]
};
