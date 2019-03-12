const initialState = {
  activePost: null
}

function appReducer (state = initialState, action) {
  switch (action.type) {
    case "SET_ACTIVE":
      return {
        ...state,
        activePost: action.value
      }
      break;
    default:
      return state
  }
}

export default appReducer
