import fs from 'fs'
const jsonDocumentation = fs.readFileSync('./public/api/docs/content/documentation.json', 'utf-8')
const documentation = JSON.parse(jsonDocumentation)

function formatMdPath (path, page = 'Main', version = '', object = '') {
  if (!path.endsWith('.md')) path += '.md'

  if (page === 'Main') {
    return API_PATH + MD_PATH + path + MD_VERSION
  }
  if (page === 'DocsVersions') {
    return API_PATH + MD_PATH + 'version-' + path + MD_VERSION
  }
  if (page === 'DocsObject') {
    return API_PATH + MD_PATH + 'object-v' + version + '-' + path + MD_VERSION
  }
  if (page === 'DocsMethod') {
    return API_PATH + MD_PATH + 'method-v' + version + '-' + object + '-' + path + MD_VERSION
  }
}

function setPathByFilenameField (filenameList = [], page = 'Main', version = '', object = '') {
  filenameList.forEach(filename => {
    if (typeof filename !== 'string' || filename.length === 0) return
    const path = formatMdPath(filename, page, version, object)
    paths.push(path)
  })
}

function createFileIfEmpty (path) {
  const defaultText = `# This is an automatically created file in app: ${documentation['project-name']}`
  if (fs.existsSync(path)) {
    if (fs.readFileSync(path).length > 0) {
    } else {
      fs.writeFileSync(path, defaultText, 'utf-8')
    }
  } else {
    fs.writeFileSync(path, defaultText, 'utf-8')
  }
}

const API_PATH = './public/api/docs/content/'
const MD_PATH = 'md/'
const MD_VERSION = ''
const paths = []

// Gen Main files from filename
setPathByFilenameField(documentation['project-path-content'].main, 'Main')
// Gen content files from filename

for (const version in documentation.content.versions) {
  // Gen version files from filename
  const filenameVersion = documentation.content.versions[version].filename[0]
  setPathByFilenameField(documentation.content.versions[version].filename, 'DocsVersions')
  for (const object in documentation.content.versions[version].objects) {
    // Gen object files from filename
    const filenameObject = documentation.content.versions[version].objects[object].filename[0]
    setPathByFilenameField(documentation.content.versions[version].objects[object].filename, 'DocsObject', filenameVersion)
    for (const method in documentation.content.versions[version].objects[object].methods) {
      // Gen object files from filename
      setPathByFilenameField(documentation.content.versions[version].objects[object].methods[method].filename, 'DocsMethod', filenameVersion, filenameObject)
    }
  }
}

if (paths.length <= 0) {
  console.log('Empty file')
  process.exit(1)
}

paths.forEach(path => {
  createFileIfEmpty(path)
})
