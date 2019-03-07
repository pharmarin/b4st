import React from 'react';
import { connect } from 'react-redux';
import ListView from './ListView';

class App extends React.Component {
  constructor () {
    super()
    this.state = {
      data: data
    }
  }

  componentDidMount () {
    this.props.dispatch({
      type: "LOADING_SUCCESS",
      postType: 'aromatherapie',
      value: data
    })
  }

  render () {
    console.log(this.props)
    return (
      <div className="row">
        <div className="col-sm-4">
          <ListView data={this.state.data} />
        </div>
        <div className="col-sm-8 h-100"></div>
      </div>
    )
  }
}

const mapStateToProps = (state) => {
  return {
    posts: state.pharmaPosts,
    isLoading: {
      pharmacie: state.pharmaIsLoading,
      aromatherapie: state.aromaIsLoading,
      phytotherapie: state.phytoIsLoading
    }
  }
}

export default connect(mapStateToProps)(App)
