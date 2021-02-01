module.exports = {
  content: [
    "htdocs/themes/berlin_mcjc/**/*.php",
    "htdocs/themes/berlin_mcjc/**/*.js",
  ],
  css: ["htdocs/themes/berlin_mcjc/css/style.css"],
  output: "htdocs/themes/berlin_mcjc/css/style.css",
  safelist: {
    greedy: [
      /webp/,
      /hero-image/,
      /playing/,
      /lity/,
      /admin-bar/,
      /truncated/,
      /search/,
      /simple-pages-text-wrapper/,
      /blockquote/,
      /cite/,
    ],
  },
};
