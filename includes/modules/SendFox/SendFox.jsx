// External Dependencies
import React, { Component } from 'react';


import AjaxComponent from "./../Base/AjaxComponent";

// Internal Dependencies
import './style.css';


class SendFox extends AjaxComponent {

  static slug = 'mfe_send_fox';

  static css(props) {
    const utils         = window.ET_Builder.API.Utils;
    const additionalCss = [];

    // Process text-align value into style
    
    if (props.title_text_align) {
      //Title
      additionalCss.push([{
        selector:    '%%order_class%% .lsf_info_title',
        declaration: `text-align: ${ props.title_text_align};`,
      }]);
    }
    
    
      additionalCss.push([{
        selector:    '%%order_class%% .lsf_info_title',
        declaration: `color: ${ props.title_color};`,
      }]);
      
      additionalCss.push([{
        selector:    '%%order_class%% .lsf_info_title',
        declaration: utils.setElementFont(props.title_select_font),
      }]);

   
      additionalCss.push([{
        selector:    '%%order_class%% .lsf_info_body',
        declaration: `text-align: ${ props.body_text_align};`,
      }]);
  

      additionalCss.push([{
        selector:    '%%order_class%% .lsf_info_body',
        declaration: `color: ${ props.body_color};`,
      }]);

      additionalCss.push([{
        selector:    '%%order_class%% .lsf_info_body',
        declaration: utils.setElementFont(props.body_select_font),
      }]);

      
      //Inputs
      additionalCss.push([{
        selector:    '%%order_class%% .lsf_fields_bg',
        declaration: `background-color: ${props.fields_bg_color};`,
      }]);

      additionalCss.push([{
        selector:    '%%order_class%% .lsf_fields_color::placeholder ',
        declaration: `color: ${props.fields_color};`,
      }]);

      additionalCss.push([{
        selector:    '%%order_class%% .lsf_fields_color:focus ',
        declaration: `color: ${props.fields_color};`,
      }]);

      additionalCss.push([{
        selector:    '%%order_class%% .lsf_fields_color',
        declaration: `color: ${props.fields_color};`,
      }]);
 

    // Process font option into style
    if (props.select_font) {
      additionalCss.push([{
        selector:    '%%order_class%% .typography-fields',
        declaration: utils.setElementFont(props.select_font),
      }]);
    }

    // Process color preview color
    if (props.color) {
      additionalCss.push([{
        selector:    '%%order_class%% .colorpicker-preview.color',
        declaration: `background-color: ${props.color};`,
      }]);
    }

    // Process color preview color alpha
    if (props.color_alpha) {
      additionalCss.push([{
        selector:    '%%order_class%% .colorpicker-preview.color-alpha',
        declaration: `background-color: ${props.color_alpha};`,
      }]);
    }
    

    return additionalCss;
  }

  /**
   * Custom method to render button UI
   *
   * @return {string|React.Component}
   */
  _renderButton() {
    const props              = this.props;
    const utils              = window.ET_Builder.API.Utils;
    const buttonTarget       = 'on' === props.url_new_window ? '_blank' : '';
    const buttonIcon         = props.button_icon ? utils.processFontIcon(props.button_icon) : false;
    const buttonClassName    = {
      et_pb_button:             true,
      et_pb_custom_button_icon: props.button_icon,
    };

    console.log("Color boton"+props.send);

    if( !props.send ){  
      //return '';
    }


    return (
     <div className='et_pb_button_wrapper'>
        <a style={ props.button }
          className={ utils.classnames(buttonClassName) }
          rel={utils.linkRel(props.button_rel)}
          data-icon={buttonIcon}
        >
          {props.send}
        
        </a>
      </div>
    );
  }


  _checkName()
  {
    if( this.props.check_name === 'on' )
    {
      let classHaftFisrtName = 'et_pb_sendfox_field';
      if( this.props.first_name_fullwidth === 'off'){
        classHaftFisrtName = 'et_pb_sendfox_field et_pb_sendfox_field_half';
      }
      return (
        <p className={classHaftFisrtName}><input type='text' className='lsf_fields_bg lsf_fields_color input' placeholder={ this.props.label_name } name='first_name' required /></p>
      )
    }
  }

  _checkLastName()
  {
    if( this.props.check_last_name === 'on' )
    {

      let classHaftLastName = 'et_pb_sendfox_field';
      if( this.props.last_name_fullwidth === 'off'){
        classHaftLastName = 'et_pb_sendfox_field et_pb_sendfox_field_half';
      }
      

      return (
        <p className={classHaftLastName}><input type='text' className='lsf_fields_bg lsf_fields_color input'  placeholder={ this.props.label_last_name } name='last_name' required /></p>
      )
    }
  }

  _email()
  {
      let classHaftEmail = 'et_pb_sendfox_field';
      if( this.props.email_fullwidth === 'off'){
        classHaftEmail = 'et_pb_sendfox_field et_pb_sendfox_field_half';
      }
      

      return (
        <p className={classHaftEmail}><input type='text' className='lsf_fields_bg lsf_fields_color input' placeholder={ this.props.email } name='email' required /></p>
      )
 
  }


  _checkTile(){
    //console.log( this.props.title );
    if( this.props.title !== undefined )
    {
      return this.props.title;
    }else{
      return "Here goes the title";
    }
  }


  _checkBody(){
 
    let body = "Your content goes here. Edit or remove this text in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.";
    if( this.props.body !== undefined )
    {
      return this.props.body;
    }else{
      return body;
    }
  }

  

  render()
  { 


    const bg = this.props.background_color;
    let classSF;

    if (!bg ) {
      
      classSF = "holderSendFox holderSendFoxBg";

    } else {

      classSF = "holderSendFox holderSendFoxColor";

    }


    const bg_layout = this.props.background_layout;
    let classTitle = "lsf_info_title "+bg_layout;
    let classBody = "lsf_info_body "+bg_layout;
    let classForm = "sendfox-form "+bg_layout;


    const directionLayout = this.props.layout;

    let orderLayoutLeft = "";
    let orderLayoutRight = "";
    if( directionLayout === "right_left" )
    {
      orderLayoutLeft = "directionRF"
    }else if( directionLayout === "top_bottom" )
    {
      orderLayoutLeft = " fullwidth ";
      orderLayoutRight = " fullwidth ";
    }
    else if( directionLayout === "bottom_top" )
    {
      orderLayoutLeft = " directionRF fullwidth ";
      orderLayoutRight = " fullwidth ";
    }

    let classHolderLeft = " lsf_left lsf_left_description "+orderLayoutLeft
    let classHolderRight = " lsf_right "+orderLayoutRight

    return (

      <div>
        <div className={classSF}>
          <div className={classHolderLeft}>
            <div id='lsf_title' className={classTitle} >{ this._checkTile() }</div> 

            <div id='lsf_body' className={classBody} style={{ whiteSpace: "pre-wrap" }} dangerouslySetInnerHTML={{  __html:this._checkBody() }}></div>
          </div>
          <div className={classHolderRight} ref="lsf_right">
            <form method='post' action='#' className={classForm} id='' data-async='true'>
              <div id="et-boc">
                <div class="et-l">
                  <div class='holderFlex'> 
                    { this._checkName() }
                    
                    { this._checkLastName() }
                    
                    { this._email() }
                  </div>
              
                  <p className="">
                    <div id=""> 
                      
                        {this._renderButton()}
                    </div>
                    
                  </p>
                </div>
              </div>

            </form>
              
          </div>
          <div className='lsf_clear'></div>
        </div>
      </div>
      );
  }
}

export default SendFox;
