const { defineConfig } = require('@vue/cli-service')
const path = require('path')

module.exports = defineConfig({
  runtimeCompiler: true,
  transpileDependencies: true,

  configureWebpack: {
    resolve: {
      alias: {
        '@': path.join(__dirname, 'src/'),
        '~': path.resolve(__dirname, 'src/'),
      },
    },
  },

  css: {
    loaderOptions: {
      sass: {
        additionalData: '@import \'@/assets/styles/_override.scss\';',
      },
    },
  },

  pluginOptions: {
    vuetify: {
			// https://github.com/vuetifyjs/vuetify-loader/tree/next/packages/vuetify-loader
		},
  },

  productionSourceMap: false,
})
