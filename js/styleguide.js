function rgb2hex(rgb) {
  rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
  return (rgb && rgb.length === 4) ? '#' +
    ('0' + parseInt(rgb[1], 10).toString(16)).slice(-2) +
    ('0' + parseInt(rgb[2], 10).toString(16)).slice(-2) +
    ('0' + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
}

const headings = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
const wrap = document.getElementById('styleguide-headings');

if (wrap) {
  for (let index = 0; index < headings.length; index += 1) {
    const heading = wrap.querySelector(headings[index]);

    if (heading) {
      const headingStyles = getComputedStyle(heading);
      const label = `(${headingStyles.fontWeight}, ${headingStyles.fontSize}, ${rgb2hex(headingStyles.color)})`;

      const span = document.createElement('SPAN');
      span.className += 'styleguide__heading-styles';
      const text = document.createTextNode(label);

      span.appendChild(text);
      heading.appendChild(span);
    }
  }
}
