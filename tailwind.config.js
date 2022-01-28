module.exports = {
    mode: "jit",
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./packages/*/src/resources/views/**/*.blade.php",
    ],
    theme: {
      extend: {
      },
    },
    variants: {
      extend: {},
    },
    plugins: [],
  }