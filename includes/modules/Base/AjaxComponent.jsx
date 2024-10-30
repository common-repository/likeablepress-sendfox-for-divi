// External Dependencies
import React, { Component } from 'react';

import $ from 'jquery';
import { findDOMNode } from 'react-dom';

// Internal Dependencies
//import './style.css';

class AjaxComponet extends Component {

  constructor(props)
  {
    super(props);
    this.state = {
      isLoaded: false,
      result: null,
      error: null,
    }
  }

  componentDidMount()
  {
    this._reload(this.props);

    
  }

  componentWillReceiveProps(newProps)
  {
    const oldProps = this.props;
    if( this._shouldReload(oldProps, newProps) )
    {
      this.setState({
        isLoaded:false,
        result: null,
        error: null
      });
      this._reload(newProps);
    }
  }

  _shouldReload(oldProps, newProps)
  {
      return false;
  }

  _reloadData(props)
  {
      return null;
  }

  _reload(props)
  {
    const component = this;
    $.ajax({
      url: window.et_fb_options.ajaxurl,
      type: 'POST',
      data: this._reloadData(props),
      success: function(response){
        component.setState({
          isLoaded: true,
          result: response,
        });
        console.log("Success", response);
      },
      error: function(){
        component.setState({
          isLoaded: true,
          error: "Oops, there was an error",
        });

        console.log("Error");
      }
    })
  }



  render() {
    //const Content = this.props.content;

    if( this.state.isLoaded )
    {
      if(this.state.error)
      {
        return(<div>{this.state.error}</div>);
      }
      else
      {
        return this._render();
      }
    }else{
      return (<div>Nada</div>);
    }

    /*
        return (
            
            <div id='demo'>
            
            { this.props.content1 } - 
            { this.props.content2 } - 
            { this.props.content3 } - 
            { this.props.content4 } - 
            { this.props.content5 } - 
            { this.props.content6 } - 
            { this.props.content7 }
            ->
            { this.props.file1 } ->
            { this.props.file2 } 

            <div>
                <div>Checkboxes:{ this.props.checkboxes }</div>
                <div>Red:{ this.props.checkboxes && this.props.checkboxes.split("|")[0] === 'on' ? "yes" : "no" }</div>
                <div>Green:{ this.props.checkboxes && this.props.checkboxes.split("|")[1] === 'on' ? "yes" : "no" }</div>
                <div>Blue:{ this.props.checkboxes && this.props.checkboxes.split("|")[2] === 'on' ? "yes" : "no" }</div>
            </div>
            </div>
        
        
        
        );
    */
  }

  _render()
  {
    return null;
  }
}

export default AjaxComponet;
