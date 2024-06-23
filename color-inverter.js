function invertColors() {
  const stylesheets = document.styleSheets;

  // Добавляем класс с анимацией ко всем элементам
  document.querySelectorAll('*').forEach(el => {
      el.classList.add('invert-animation');
  });

  for (let sheet of stylesheets) {
      try {
          const rules = sheet.cssRules;
          for (let rule of rules) {
              if (rule.style) {
                  for (let style of rule.style) {
                      let value = rule.style.getPropertyValue(style);
                      if (value && isColor(value)) {
                          setTimeout(() => {
                              const newValue = invertColor(value);
                              if (newValue !== value) {
                                  // Применяем изменение цвета с анимацией
                                  rule.style.setProperty(style, newValue, rule.style.getPropertyPriority(style));
                              }
                          }, 0);
                      }
                  }
              }
          }
      } catch (e) {
          console.error("Ошибка доступа к стилям: ", e);
      }
  }

  // Удаляем класс после завершения анимации
  setTimeout(() => {
      document.querySelectorAll('*').forEach(el => {
          el.classList.remove('invert-animation');
      });
  }, 2000); // Время анимации должно совпадать с временем transition
}

function isColor(value) {
  return /^#[0-9A-F]{6}$/i.test(value) || /^(rgb|hsl)a?\(/.test(value) || namedColors[value.toLowerCase()];
}

function invertColor(color) {
  if (color === 'rgb(0, 0, 255)') {
      return 'rgb(255, 0, 0)';
  } else if (color === 'rgb(255, 0, 0)') {
      return 'rgb(0, 0, 255)';
  }

  if (color.startsWith('#')) {
      if (isRed(hexToRgb(color))) {
          return 'rgb(0, 0, 255)';
      }
      const rgb = hexToRgb(color);
      const inverted = rgb.map(c => 255 - c);
      return rgbToHex(...inverted);
  } else if (color.startsWith('rgb')) {
      const rgba = color.match(/\d+(\.\d+)?/g).map(Number);
      if (isRed(rgba.slice(0, 3))) {
          return 'rgb(0, 0, 255)';
      }
      const inverted = rgba.slice(0, 3).map(c => 255 - c);
      if (rgba.length === 4) {
          return `rgba(${inverted.join(', ')}, ${rgba[3]})`;
      } else {
          return `rgb(${inverted.join(', ')})`;
      }
  } else if (color.startsWith('hsl')) {
      let [h, s, l] = color.match(/\d+/g).map(Number);
      if (isRed(hslToRgb(h, s, l))) {
          return 'rgb(0, 0, 255)';
      }
      h = (h + 180) % 360;
      l = 100 - l;
      return `hsl(${h}, ${s}%, ${l}%)`;
  } else {
      if (isRed(hexToRgb(namedColors[color.toLowerCase()]))) {
          return 'rgb(0, 0, 255)';
      }
      const rgb = hexToRgb(namedColors[color.toLowerCase()]);
      const inverted = rgb.map(c => 255 - c);
      return rgbToHex(...inverted);
  }
  return color;
}

function isRed(rgb) {
  return rgb[0] > 200 && rgb[1] < 50 && rgb[2] < 50;
}

function hexToRgb(hex) {
  let r = parseInt(hex.slice(1, 3), 16),
      g = parseInt(hex.slice(3, 5), 16),
      b = parseInt(hex.slice(5, 7), 16);
  return [r, g, b];
}

function rgbToHex(r, g, b) {
  return "#" + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
}

function hslToRgb(h, s, l) {
  s /= 100;
  l /= 100;
  let c = (1 - Math.abs(2 * l - 1)) * s,
      x = c * (1 - Math.abs((h / 60) % 2 - 1)),
      m = l - c / 2,
      r = 0,
      g = 0,
      b = 0;
  if (0 <= h && h < 60) {
      r = c;
      g = x;
      b = 0;
  } else if (60 <= h && h < 120) {
      r = x;
      g = c;
      b = 0;
  } else if (120 <= h && h < 180) {
      r = 0;
      g = c;
      b = x;
  } else if (180 <= h && h < 240) {
      r = 0;
      g = x;
      b = c;
  } else if (240 <= h && h < 300) {
      r = x;
      g = 0;
      b = c;
  } else if (300 <= h && h < 360) {
      r = c;
      g = 0;
      b = x;
  }
  r = Math.round((r + m) * 255);
  g = Math.round((g + m) * 255);
  b = Math.round((b + m) * 255);
  return [r, g, b];
}

const namedColors = {
  "black": "#000000",
  "white": "#ffffff",
  "red": "#ff0000",
  "lime": "#00ff00",
  "blue": "#0000ff",
  "yellow": "#ffff00",
  "cyan": "#00ffff",
  "magenta": "#ff00ff",
  "silver": "#c0c0c0",
  "gray": "#808080",
  "maroon": "#800000",
  "olive": "#808000",
  "green": "#008000",
  "purple": "#800080",
  "teal": "#008080",
  "navy": "#000080",
  // Добавьте другие именованные цвета при необходимости
};

document.querySelector('button').addEventListener('click', invertColors);
