import { createApiReference } from '@scalar/api-reference'
import '@scalar/api-reference/style.css'

createApiReference('#app', {
  url: '/docs/openapi.yaml',
})
