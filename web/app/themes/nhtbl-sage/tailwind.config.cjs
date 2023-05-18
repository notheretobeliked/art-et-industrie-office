// https://tailwindcss.com/docs/configuration
module.exports = {
  content: ['./index.php', './app/**/*.php', './resources/**/*.{php,vue,js}'],
  safelist: [
    'editor-post-title',
    'p-name',
    'editor-styles-wrapper',
    'event_tribe_venue',
    'event_tribe_organizer',
    'event_url',
    'event_cost',
    'eventtable',
    'the-content',
    'alignwide',
    'alignfull',
  ],
  theme: {
    fontFamily: {
      serif: ['Signifier', 'Georgia', 'serif'],
      ui: ['Pirelli', 'sans-serif'],
      display: ['Pirelli', 'sans-serif'],
    },
    fontSize: {
      sm: [
        '1rem',
        {
          lineHeight: '1.2rem',
        },
      ],
      base: [
        '1.25rem',
        {
          lineHeight: '1.5',
        },
      ],
      lg: [
        '1.4rem',
        {
          lineHeight: '1.5',
        },
      ],
      xl: [
        '1.7rem',
        {
          lineHeight: '1.5',
        },
      ],
      '2xl': [
        '1.9rem',
        {
          lineHeight: '1.1',
        },
      ],
      '3xl': [
        '2.4rem',
        {
          lineHeight: '1.1',
        },
      ],
      '4xl': [
        '2.8rem',
        {
          lineHeight: '1',
        },
      ],
      '5xl': [
        '3.8rem',
        {
          lineHeight: '1.1',
        },
      ],
    },
    colors: {
      black: '#2F2E2B',
      white: '#F5F3EC',
      gray: {
        500: '#807F7C',
        100: '#DFDBDA',
      },
      transparent: 'transparent',
    },
    fluidTypography: {
      remSize: 12,
      minScreenSize: 400,
      maxScreenSize: 1680,
      minTypeScale: 1.22,
      maxTypeScale: 1.618,
      lineHeight: 1.2,
    },
    extend: {
      colors: {}, // Extend Tailwind's default colors
      spacing: {
        128: '32rem',
      },
      gridTemplateColumns: {
        // Simple 16 column grid
        'event': '1fr 5fr 1fr',
      },
    },
  },
  plugins: [
    require('tailwindcss-text-fill-stroke'), // no options to configure
  ],
};
