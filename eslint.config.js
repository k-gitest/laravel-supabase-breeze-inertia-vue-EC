import js from "@eslint/js";
import ts from "typescript-eslint";
import vue from "eslint-plugin-vue";
import vueParser from "vue-eslint-parser";
import globals from "globals";

export default [
  { ignores: [/* ignores */] },
  js.configs.recommended,
  ...ts.configs.recommendedTypeChecked,
  ...vue.configs["flat/recommended"],
  {
    languageOptions: {
      parser: vueParser,
      parserOptions: {
        ecmaVersion: 2020,
        parser: ts.parser,
        extraFileExtensions: [".vue"],
        sourceType: "module",
        project: ["./tsconfig.json"],
      },
      globals: {
        ...globals.browser,
        ...globals.node,
        route: true,
      },
    },
  },
  {
    files: ["*.vue", "**/*.vue"],
    rules: {
      "vue/html-indent": "off",
      "vue/singleline-html-element-content-newline": "off",
      "vue/html-self-closing": "off",
      "vue/max-attributes-per-line": "off",
      "vue/attributes-order": "off",
      "vue/multi-word-component-names": "off",
      "vue/no-v-text-v-html-on-component": "off",
      "vue/camelcase": "off",
      "vue/prop-name-casing": "off",
    },
  },
];