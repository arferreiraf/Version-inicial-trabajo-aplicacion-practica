'''import cv2 as c

img = c.imread('ii/apple.png')
gris_img = c.cvtColor(img, c.COLOR_BGR2GRAY)
c.imshow('jj',img)
c.imshow( 'jj gris', gris_img)
c.waitKey(0)
c.destroyAllWindows()'''

'''import cv2  ##---- script para visualizar webcam

def show_webcam():
	cam = cv2.VideoCapture(1)
	while True:
		n,img = cam.read()
		
		cv2.imshow('my webcam', img)
		if cv2.waitKey(1) == 27: 
			break


	cv2.destroyAllWindows()

def main():

	show_webcam()

if __name__ == '__main__':
	main()'''
from tkinter import*
import time  ##--**-- OPENCV tomar fotografia ---**
import cv2
from PIL import Image , ImageTk

def otro():
	
	t = Toplevel()

	nom = StringVar()

	t.geometry('1000x690+100+10')
	t_t = Frame(t)
	t_t.configure(width=135, height=60, bg='#26C281')
	t_t.place(x=800, y=650)
	ent = Entry(t_t,textvariable=nom,width=25)
	ent.place(x=5,y= 2)
	tit_ent = Label(t, text='nombre archivo').place(x=810,y=630)
	ent.focus_set()


	def captura(arch):
	

		camara = cv2.VideoCapture(1)

		time.sleep(0.05)  #--se detiene el flujo de datos de videocapture---
		return_value, imagen = camara.read()
		cv2.imwrite("{0}.png".format(arch), imagen)
		del(camara) 
		# se utiliza la clase y metodo 'Image.open()'de la biblioteca PIL 
		imgS = Image.open('{0}.png'.format(arch))
		imagenS = imgS.resize((290,290))
		Simg    = ImageTk.PhotoImage(imagenS)
		im = Label(t, image=Simg, width=300, height=300).place(x=10, y=10)
 	
		t.mainloop()

	toma_foto = Button(t,command= lambda: captura(nom.get()), text='Tomar Foto').place(x=900, y=650)


	#t.mainloop()


