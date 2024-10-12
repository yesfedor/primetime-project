const fs = require('fs')
const configRaw = fs.readFileSync('config.json', 'utf8')
const config = JSON.parse(configRaw)

let configFileRaw = fs.readFileSync('engine/boot/config.php', 'utf-8')

for (const key in config) {
  const value = config[key]
  configFileRaw = configFileRaw.replace(`{${key}}`, value)
}

fs.writeFileSync('engine/boot/config.php', configFileRaw, 'utf-8')
