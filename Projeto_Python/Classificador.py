# -*- coding: utf-8 -*-
"""
Created on Thu Jun  3 14:44:44 2021

@author: ffcpi
"""

import MySQLdb
import patoolib
import os
from ctypes import *
import subprocess

uploadURL = "C:/wamp64/www/Projeto/Uploads"
ExtractedFilesURL = "C:/wamp64/www/Projeto/Projeto_Python/Extracted_Files"


servername = "127.0.0.1"
databaseName = "Classificador_Codigo"
username = "root"
password = ""


#Connect DB

db = MySQLdb.connect(host=servername,    # your host, usually localhost
                     user=username,         # your username
                     passwd=password,  # your password
                     db=databaseName)        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()


#File Path from the uploaded files
filesPath = uploadURL


#Programa corre infintamente
#é sempre corrigido o primeiro ficheiro
#das pastas zipadas e depois é retirado
#destas e colocado o ficheiro final noutra pasta



while True: 
    #Get file names
    files = []
    for (dirpath, dirnames, filenames) in os.walk(filesPath):
        files.extend(filenames)
        break
        
    print(files[0])
        
    
    #Get project ID to get the casos_Teste
    cur.execute("SELECT ProjetoID FROM ficheiro where Nome=%s", (files[0], ))
    
    for row in cur.fetchall():
        print("Projeto ID: ", row[0])
        projectID = row[0]
    
    
    
    
    
    #Get Casos_Teste
    cur.execute("SELECT Output FROM casos_teste where ProjetoID=%s", (projectID, ))
    Outputs = []
    for row in cur.fetchall():
        Outputs.extend(row)
    
    #print("\n\nOutputs: ", Outputs)
        
    cur.execute("SELECT Input FROM casos_teste where ProjetoID=%s", (projectID, ))
    Inputs = []
    for row in cur.fetchall():
        Inputs.extend(row)
    
    #print("\n\nInputs: ", Inputs)
    db.close()
    
    
    
    
    
    
    #Extract the file
    fileToExtract = filesPath + "/" + files[0]
    
    #Extract file
    try:
        patoolib.extract_archive(fileToExtract, outdir=ExtractedFilesURL) 
    except:
        #Means that a file already exists there 
        if os.path.exists(ExtractedFilesURL):
            os.remove(ExtractedFilesURL)

        
    
    #Get Extracted file names
    ExtractedFiles = []
    for (dirpath, dirnames, filenames) in os.walk(ExtractedFilesURL):
        ExtractedFiles.extend(filenames)
        break
    
    print("\n\n", ExtractedFiles)
    
        # #Execute the file
    fileToExecute = ExtractedFilesURL + "/" + ExtractedFiles[0];
    phpURL = "C:\\wamp64\\bin\\php\\php7.4.9\\php.exe"
    #print("Path do ficheiro a executar: ", fileToExecute)
        
    #Input = [1, 2]
    #Get the results
    OutputsObtidos = []
    for row in Inputs:
        proc = subprocess.Popen([phpURL, fileToExecute, " " + str(row)], shell=True, stdout=subprocess.PIPE)
        output = proc.stdout.read()
        OutputsObtidos.extend(output)
        print(output)

    
    #Convert from ascii to string
    OutputsObtidosFinais = ''.join(chr(i) for i in OutputsObtidos)
    #print((OutputsObtidosFinais[0]))
    
    #Compare the results
    numOutputsTotais = len(OutputsObtidosFinais)
    correctOutputs = 0
    index = 0
    
    #Output = [3, 4]
    for row in OutputsObtidosFinais:
        print("\rOutput esperado" + str(Outputs[index]))
        print("\rOutput Obtido" + str(row))
        if(str(Outputs[index]) == str(row)):
            correctOutputs+=1
        
        index+=1


    #Get grade
    gradePerQuestion = 20/index
    finalGrade = gradePerQuestion*correctOutputs
    
    print("A sua nota é: " + str(finalGrade))
        
    
    
   
    #Execute php file

    
    
    