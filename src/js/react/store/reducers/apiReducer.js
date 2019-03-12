const initialState = {
  pharmaIsLoading: false,
  aromaIsLoading: false,
  phytoIsLoading: false,
  pharmaPosts: [],
  aromaPosts: postsData,
  phytoPosts: [],
}

function apiActions (state = initialState, action) {
  switch (action.type) {
    case "LOADING_BEGIN":
      switch (action.postType) {
        case 'all':
          return {
            ...state,
            pharmaIsLoading: true,
            aromaIsLoading: true,
            phytoIsLoading: true,
          }
        case 'posts':
          return {
            ...state,
            pharmaIsLoading: true
          }
        case 'aromatherapie':
          return {
            ...state,
            aromaIsLoading: true
          }
        case 'phytotherapie':
          return {
            ...state,
            phytoIsLoading: true
          }
        default:
          return state
      }
    case "LOADING_SUCCESS":
      switch (action.postType) {
        case 'posts':
          return {
            ...state,
            pharmaIsLoading: false,
            pharmaPosts: action.value
          }
        case 'aromatherapie':
          return {
            ...state,
            aromaIsLoading: false,
            aromaPosts: action.value
          }
        case 'phytotherapie':
          return {
            ...state,
            phytoIsLoading: false,
            phytoPosts: action.value
          }
        default:
          return state
      }
    default:
      return state
  }
}

export default apiActions
