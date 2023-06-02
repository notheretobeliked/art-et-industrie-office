// https://tailwindcss.com/docs/configuration
module.exports = {
  darkMode: 'class',
  content: ['./index.php', './app/**/*.php', './resources/**/*.{php,vue,js}'],
  safelist: [
    'grid-cols-mapandcontent',
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
    'has-lg-font-size',
    'wp-block-heading',
    'h-12',
    'w-48',
    'h-128',
    'h+132',
    'object-center',
    'object-cover',
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
      remSize: 13,
      minScreenSize: 400,
      maxScreenSize: 1680,
      minTypeScale: 1.42,
      maxTypeScale: 1.818,
      lineHeight: 1.2,
    },
    blur: {
      'lg': '11px',
      'sm': '6px',
      'xl': '40px',
    },
    extend: {
      colors: {}, // Extend Tailwind's default colors
      spacing: {
        128: '32rem',
        136: '36rem',
      },
      gridTemplateColumns: {
        // Simple 16 column grid
        mapandcontent: '3fr 5fr',
        event: '1fr 5fr 1fr',
        menu: '1fr 4fr 1fr',
        logo: '8fr 1fr 8fr',
      },
    },
  },
  plugins: [
    require('tailwind-fluid-typography'),
    require('tailwindcss-text-fill-stroke'), // no options to configure
  ],
}
