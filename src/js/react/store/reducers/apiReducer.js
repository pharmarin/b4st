const initialState = {
  aromaPosts: postsData
};

function apiReducer(state = initialState, action) {
  if (action.type === "LOADING_SUCCESS") {
    return Object.assign({}, state, {
      aromaPosts: []
    });
  }
  return state;
}

export default apiReducer;
