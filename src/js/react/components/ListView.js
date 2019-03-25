import React from 'react';
import { connect } from 'react-redux';
import { RenderHTML } from './RenderHTML';

class ListView extends React.Component {

  constructor(props) {
    super(props);

    this.setWrapperRef = this.setWrapperRef.bind(this);
    this.handleClickOutside = this.handleClickOutside.bind(this);
  }

  componentDidMount() {
    document.addEventListener('mousedown', this.handleClickOutside);
  }

  componentWillUnmount() {
    document.removeEventListener('mousedown', this.handleClickOutside);
  }

  /**
   * Set the wrapper ref
   */
  setWrapperRef(node) {
    this.wrapperRef = node;
  }

  /**
   * Alert if clicked on outside of element
   */
  handleClickOutside(event) {
    if (this.wrapperRef && !this.wrapperRef.contains(event.target) && this.props.activePost) {
      this._currentSection = null
      this.props.dispatch({
        type: "SET_ACTIVE",
        value: null
      })
    }
  }

  _itemSelected (id) {
    this._currentSection = null
    this.props.dispatch({
      type: "SET_ACTIVE",
      value: id
    })
  }

  _isSection (item) {
    if (item) {
      if (item.title.rendered.charAt(0) !== this._currentSection) {
        this._currentSection = item.title.rendered.charAt(0)
        return true
      } else {
        return false
      }
    } else {
      return (
        <li className="list-group-item section disabled" key={this._currentSection}>
          {
            this._currentSection
          }
        </li>
      )
    }
  }

  _renderItem (item, index) {
    return (
      <li
        className={
          item.id === this.props.activePost ?
           "list-group-item list-group-item-action active"
           : "list-group-item list-group-item-action"
        }
        key={ index }
        onClick={ this._itemSelected.bind(this, item.id) }
        >
        {
          RenderHTML(item.title.rendered)
        }
      </li>
    )
  }

  render () {
    return (
      <div ref={this.setWrapperRef}>
        <ul className="list-group">
          {
            this.props.data ? this.props.data.map((item, index) =>
              {
                return this._isSection(item) ?
                [this._isSection(), this._renderItem(item, index)] : this._renderItem(item, index)
              }
            ) : null
          }
        </ul>
      </div>
    )
  }
}

const mapStateToProps = (state) => {
  //console.log("MapState", state)
  return {
    activePost: state.appReducer.activePost
  }
}

export default connect(mapStateToProps)(ListView)
